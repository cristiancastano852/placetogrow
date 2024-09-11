<?php

namespace App\Contracts;

use App\Models\Payment;
use App\Models\Subscription;
use App\Services\Payments\PaymentResponse;
use App\Services\Payments\QueryPaymentResponse;
use Illuminate\Http\Client\Response as ClientResponse;

interface PaymentGateway
{
    public function prepare(): self;

    public function buyer(array $buyer): self;

    public function payer(array $payer): self;

    public function payment(Payment $payment): self;

    public function process(): PaymentResponse;

    public function processCollect(): ClientResponse;

    public function subscription(Subscription $subscription): self;

    public function createSubscription();

    public function checkSubscription(string $subscription_identifier): ClientResponse;

    public function get(string $process_identifier): QueryPaymentResponse;

    public function paymentCollect(Payment $payment, Subscription $subscription): self;
}
