<?php

namespace App\Repositories;

use App\Constants\PaymentStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;

class PaymentRepository
{
    protected array $data = [];

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

    public function buyer($data): self
    {
        $this->data['buyer'] = ([
            'name' => $data->name,
            'last_name' => $data->last_name,
            'email' => $data->email,
            'document_number' => $data->document_number,
            'document_type' => $data->document_type,
        ]);

        return $this;
    }

    public function getBuyerData(): array
    {
        return $this->data['buyer'];
    }
}