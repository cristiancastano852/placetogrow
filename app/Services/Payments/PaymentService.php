<?php

namespace App\Services\Payments;

use App\Constants\InvoiceStatus;
use App\Constants\PaymentStatus;
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
            Log::error('Payment process identifier is empty', ['payment' => $this->payment]);

            return $this->payment;
        }
        $response = $this->gateway->prepare()
            ->get($this->payment->process_identifier);

        $invoice_id = $this->payment->invoice_id ?? null;
        if ($response->status->name === PaymentStatus::APPROVED->value && ! empty($invoice_id)) {
            Log::info('Invoice approved', ['payment' => $this->payment]);
            $this->payment->invoice()->update(['status' => InvoiceStatus::PAID]);
        }

        return tap($this->payment)->update([
            'status' => $response->status,
        ]);

    }
}
