<?php

namespace App\Http\Controllers;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Factories\PaymentDataProviderFactory;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Microsites;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, PaymentDataProviderFactory $factory)
    {
        $user = User::find(Auth::user()->id);
        $microsite_id = $request->microsite_id;
        $microsite = Microsites::find($microsite_id);

        $paymentRepository = new PaymentRepository();
        $payment = $paymentRepository->create($request->all(), $user, $microsite);

        $buyerData = $paymentRepository->buyer($request)->getBuyerData();

        $paymentService = $factory->make($payment, $microsite);
        $response = $paymentService->create($buyerData);
        if ($response->status === 'exception') {
            Log::error('Payment creation exception', [
                'buyer' => $buyerData,
                'payment' => $payment,
                'message' => $response->message,
            ]);

            return back()->withErrors(['message' => $response->message]);
        }

        return Inertia::location($response->url);
    }

    public function show(Payment $payment): \Inertia\Response
    {
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $payment->gateway,
        ]);

        // if ($payment->status === PaymentStatus::PENDING->value) {
        $payment = $paymentService->query();
        // }

        return Inertia::render('Payments/Show', [
            'payment' => $payment,
        ]);
    }

    public function transactions(): \Inertia\Response
    {
        $user = Auth::user();
        $payments = [];
        $microsites = Microsites::all();

        $payments = Payment::transactionsByRole($user)->get();

        return Inertia::render('Payments/Transactions', [
            'payments' => $payments,
            'microsites' => $microsites,
        ]);
    }
}
