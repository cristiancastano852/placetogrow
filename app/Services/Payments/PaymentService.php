<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGateway;
use App\Contracts\PaymentService as PaymentServiceContract;
use App\Jobs\SendConfirmationToClient;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentService implements PaymentServiceContract
{
    public function __construct(
        protected Payment $payment,
        protected PaymentGateway $gateway,
    ) {}

    public function create(array $buyer): PaymentResponse
    {
        SendConfirmationToClient::dispatch($buyer);
        $response = $this->gateway->prepare()
            ->buyer($buyer)
            ->payment($this->payment)
            ->process();

        $this->payment->update([
            'process_identifier' => $response->processIdentifier,
        ]);

        return $response;
    }

    public function query(): Payment
    {
        $process_identifier = $this->payment->process_identifier;
        if (empty($process_identifier)) {
            Log::error('Payment process identifier is empty', [
                'payment' => $this->payment,
            ]);

            return $this->payment;
        }
        $response = $this->gateway->prepare()
            ->get($this->payment->process_identifier);

        return tap($this->payment)->update([
            'status' => $response->status,
        ]);
    }

    public function collect($buyer, $subscription) {}
}
