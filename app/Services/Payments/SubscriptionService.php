<?php

namespace App\Services\Payments;

use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
use App\Contracts\PaymentGateway;
use App\Jobs\SendConfirmationToClient;
use App\Models\Microsites;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\PaymentRepository;
use App\Services\Payments\Gateways\PlacetoPayGateway;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    protected PaymentGateway $gateway;

    protected Subscription $subscription;

    public function __construct(
        protected int $expiration,
        Subscription $subscription
    ) {
        $this->gateway = new PlacetoPayGateway(now()->addMinutes($expiration));
        $this->subscription = $subscription;

    }

    public function create(User $buyer)
    {
        try {
            $buyer = [
                'name' => $buyer->name,
                'last_name' => $buyer->last_name,
                'email' => $buyer->email,
                'document_type' => $buyer->document_type,
                'document_number' => $buyer->document_number,
            ];
            SendConfirmationToClient::dispatch($buyer);
            $response = $this->gateway->prepare()
                ->buyer($buyer)
                ->subscription($this->subscription)
                ->createSubscription();

            return $response;
        } catch (\Exception $e) {
            Log::error('Payment creation exception', [
                'buyer' => $buyer,
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function query(): Subscription
    {
        $response = $this->gateway->prepare()
            ->checkSubscription($this->subscription->request_id);
        $this->UpdateSubscription($response);
        $payer = $response['request']['payer'] ?? [];
        if (! empty($payer)) {
            $this->SavePayer($payer);
        }

        return $this->subscription;
    }

    public function UpdateSubscription(ClientResponse $response)
    {
        $status = SubscriptionStatus::INACTIVE->value;
        if ($response['status']['status'] === PaymentStatus::APPROVED->value) {
            $status = SubscriptionStatus::ACTIVE->value;
            $token = $response['subscription']['instrument'][0]['value'];
            $subtoken = $response['subscription']['instrument'][1]['value'];
        }
        $this->subscription->update([
            'status' => $status,
            'token' => $token ?? null,
            'subtoken' => $subtoken ?? null,
        ]);

    }

    public function SavePayer(array $payer)
    {
        if (empty($payer)) {
            return;
        }
        $this->subscription->payer = $payer;
        $this->subscription->save();
    }

    public function ProcessPaymentCollect(): ClientResponse
    {
        $paymentRepository = new PaymentRepository();
        $user = User::find($this->subscription->user_id);
        $data = [
            'description' => $this->subscription->description,
            'amount' => $this->subscription->price,
            'fields_data' => $this->subscription->payer,
            '',
        ];
        $microsite = Microsites::find($this->subscription->microsite_id);
        $payment = $paymentRepository->create($data, $user, $microsite);
        $payment->subscription_id = $this->subscription->id;
        $microsite = $this->subscription->microsite;
        $payer = $this->subscription->payer;

        $response = $this->gateway->prepare()
            ->payer($payer)
            ->paymentCollect($payment, $this->subscription)
            ->processCollect();

        $payment->update([
            'process_identifier' => $response['requestId'],
            'status' => $response['status']['status'],
        ]);

        return $response;
    }

    public function cancel(): ClientResponse
    {
        $response = $this->gateway->prepare()
            ->invalidateTokenSubtoken($this->subscription->token, $this->subscription->subtoken);
        $this->subscription->update([
            'status' => SubscriptionStatus::CANCELED->value,
        ]);

        return $response;
    }
}
