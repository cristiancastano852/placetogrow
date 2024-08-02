<?php

namespace App\Http\Controllers;

use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Microsites;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request)
    {
        // $user = User::find(Auth::user()->id);
        // $gateway = PaymentGateway::PLACETOPAY->value;
        $gateway = $request->gateway;
        $microsite_id = $request->microsite_id;
        $microsite = Microsites::find($microsite_id);
        $currency = $microsite->currency;
        $payment = new Payment();
        $payment->reference = date('ymdHis').'-'.strtoupper(Str::random(4));
        $payment->description = $request->description;
        $payment->amount = $request->amount;
        $payment->currency = $currency;
        $payment->gateway = $gateway;
        $payment->status = PaymentStatus::PENDING;
        // $payment->user()->associate(User::first());
        // $payment->user()->associate($user);
        $payment->user()->associate($request->user_id);
        $payment->microsite()->associate($request->microsite_id);
        $payment->save();

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

        // return response()->json(['url' => $response->url]);
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
