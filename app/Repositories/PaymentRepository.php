<?php

namespace App\Repositories;

use App\Constants\PaymentStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;

class PaymentRepository
{
    public function create(array $data, User $user, $microsite, $gateway): Payment
    {
        $payment = new Payment();
        $payment->reference = date('ymdHis').'-'.strtoupper(Str::random(4));
        $payment->description = $data['description'];
        $payment->amount = $data['amount'];
        $payment->currency = $microsite->currency;
        $payment->gateway = $gateway;
        $payment->expiration = now()->addMinutes($microsite->payment_expiration);
        $payment->status = PaymentStatus::PENDING;
        $payment->user()->associate($user);
        $payment->microsite()->associate($microsite);
        $payment->fields_data = $data['fields_data'];
        $payment->save();

        return $payment;
    }
}
