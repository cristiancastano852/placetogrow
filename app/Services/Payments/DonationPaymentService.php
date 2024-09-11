<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGateway;
use App\Contracts\PaymentService as PaymentServiceContract;
use App\Jobs\SendConfirmationToClient;
use App\Models\Microsites;
use App\Models\Payment;
use App\Services\Payments\Gateways\PlacetoPayGateway;
use Illuminate\Support\Facades\Log;

class DonationPaymentService implements PaymentServiceContract
{
    protected PaymentGateway $gateway;

    public function __construct(
        protected Payment $payment,
        protected Microsites $microsite,
    ) {
        $this->gateway = new PlacetoPayGateway($payment->expiration); // Asumimos PlacetoPay para este ejemplo
    }

    public function create(array $buyer): PaymentResponse
    {
        // Lógica específica para pagos de donaciones
        try {
            SendConfirmationToClient::dispatch($buyer);

            $response = $this->gateway->prepare()
                ->buyer($buyer)
                ->payment($this->payment)
                ->process();

            $this->payment->update([
                'process_identifier' => $response->processIdentifier,
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Payment creation exception', [
                'buyer' => $buyer,
                'payment' => $this->payment,
                'message' => $e->getMessage(),
            ]);

            return new PaymentResponse(0, '', 'exception', $e->getMessage());
        }
    }

    public function query(): Payment
    {
        $response = $this->gateway->prepare()->get($this->payment->process_identifier);

        return tap($this->payment)->update([
            'status' => $response->status,
        ]);
    }

    public function collect($payer, $subscription) {}
}
