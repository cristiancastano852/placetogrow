<?php

namespace App\Http\Controllers;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request): RedirectResponse
    {

        $payment = new Payment();
        $payment->reference = date('ymdHis').'-'.strtoupper(Str::random(4));
        $payment->description = $request->description;
        $payment->amount = $request->amount;
        $payment->currency = $request->currency;
        $payment->gateway = $request->gateway;
        $payment->status = PaymentStatus::PENDING;
        // $payment->user()->associate(User::first());
        $payment->user()->associate($request->user_id);

        $payment->microsite()->associate($request->microsite_id);
        $payment->save();

        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $request->gateway,
        ]);

        $response = $paymentService->create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'document_number' => $request->document_number,
            'document_type' => $request->document_type,
        ]);

        return redirect()->away($response->url);
    }

    public function show(Payment $payment): View
    {
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $payment->gateway,
        ]);

        if ($payment->status === PaymentStatus::PENDING->value) {
            $payment = $paymentService->query();
        }

        return view('payments.show', [
            'payment' => $payment,
        ]);
    }
}
