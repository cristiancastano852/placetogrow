<?php

namespace App\Http\Controllers;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Microsites;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request)
    {
        $user = User::find(Auth::user()->id);
        $gateway = $request->gateway;
        $microsite_id = $request->microsite_id;
        $microsite = Microsites::find($microsite_id);
        $payment = new PaymentRepository();
        $payment = $payment->create($request->all(), $user, $microsite, $gateway);

        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $gateway,
        ]);

        $response = $paymentService->create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'document_number' => $request->document_number,
            'document_type' => $request->document_type,
        ]);

        return Inertia::location($response->url);

    }

    public function show(Payment $payment): \Inertia\Response
    {
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $payment->gateway,
        ]);

        if ($payment->status === PaymentStatus::PENDING->value) {
            $payment = $paymentService->query();
        }

        return Inertia::render('Payments/Show', [
            'payment' => $payment,
        ]);
    }

    public function transactions(): \Inertia\Response
    {
        $user = Auth::user();
        $payments = Payment::where('user_id', $user->id)->get();

        return Inertia::render('Payments/Transactions', [
            'payments' => $payments,
        ]);
    }

    public function transactionsByMicrosite($request): \Inertia\Response
    {
        $user = Auth::user();
        $payments = Payment::where('user_id', $user->id)->where('microsite_id', $request->microsite_id)->get();

        return Inertia::render('Payments/Transactions', [
            'payments' => $payments,
        ]);
    }
}
