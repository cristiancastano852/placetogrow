<?php

namespace App\Http\Controllers;

use App\Constants\PolicyName;
use App\Contracts\PaymentService;
use App\Factories\PaymentDataProviderFactory;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Microsites;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, PaymentDataProviderFactory $factory)
    {
        $user = User::find(Auth::user()->id);
        $micrositeId = $request->microsite_id;
        $microsite = Microsites::find($micrositeId);

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

        $this->authorize(PolicyName::VIEW, $payment);
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $payment->gateway,
        ]);

        $payment = $paymentService->query();

        return Inertia::render('Payments/Show', [
            'payment' => $payment,
        ]);
    }

    public function transactions(Request $request): \Inertia\Response
    {
        $user = Auth::user();
        $micrositeId = $request->input('microsite_id');
        $microsites = Microsites::MicrositesByUser($user)->get();
        $payments = Payment::transactionsByRole($user, $micrositeId)->get();

        return Inertia::render('Payments/Transactions', [
            'payments' => $payments,
            'microsites' => $microsites,
        ]);
    }
}
