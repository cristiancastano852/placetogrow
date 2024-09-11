<?php

namespace App\Services\Payments\Gateways;

use App\Contracts\PaymentGateway;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\Payments\PaymentResponse;
use App\Services\Payments\QueryPaymentResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlacetoPayGateway implements PaymentGateway
{
    private array $data;

    private array $config;

    public function __construct($expiration)
    {
        $this->data = [
            'expiration' => Carbon::parse($expiration)->format('c'),
            'ipAddress' => request()->ip(),
            'userAgent' => request()->userAgent(),
        ];

        $this->config = config('gateways.placetopay');
    }

    public function prepare(): self
    {
        $login = $this->config['login'];
        $secretKey = $this->config['secret_key'];
        $seed = Carbon::now()->toIso8601String();
        $nonce = Str::random();

        $tranKey = base64_encode(hash('sha256', $nonce.$seed.$secretKey, true));
        $nonce = base64_encode($nonce);

        $this->data['auth'] = [
            'login' => $login,
            'tranKey' => $tranKey,
            'nonce' => $nonce,
            'seed' => $seed,
        ];

        return $this;
    }

    public function buyer(array $data): self
    {
        $this->data['buyer'] = [
            'name' => $data['name'],
            'surname' => $data['last_name'],
            'email' => $data['email'],
            'documentType' => $data['document_type'],
            'document' => $data['document_number'],
        ];

        return $this;
    }

    public function payer(array $data): self
    {
        $this->data['payer'] = [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'documentType' => $data['documentType'],
            'document' => $data['document'],
        ];

        return $this;
    }

    public function payment(Payment $payment): self
    {
        $this->data['payment'] = [
            'reference' => $payment->reference,
            'description' => $payment->description,
            'amount' => [
                'currency' => $payment->currency,
                'total' => $payment->amount,
            ],
        ];

        $this->data['returnUrl'] = route('payments.show', $payment);

        return $this;
    }

    public function subscription(Subscription $subscription): self
    {
        $this->data['subscription'] = [
            'reference' => $subscription->reference,
            'description' => $subscription->description,
        ];
        $this->data['returnUrl'] = route('subscriptions.return', [
            'subscription' => $subscription->id,
            'microsite' => $subscription->microsite_id,
        ]);

        return $this;
    }

    public function createSubscription()
    {
        $url = $this->config['url'].'/api/session/';
        $response = Http::post($url, $this->data);

        return $response;
    }

    public function checkSubscription(string $process_identifier): Response
    {
        $url = $this->config['url'].'/api/session/'.$process_identifier;
        $response = Http::post($url, $this->data);
        Log::info('Result subscription with PlaceToPay '.json_encode($response));

        return $response;
    }

    public function paymentCollect(Payment $payment, Subscription $subscription): self
    {
        $this->data['payment'] = [
            'reference' => $payment->reference,
            'description' => $payment->description,
            'amount' => [
                'currency' => $payment->currency,
                'total' => $payment->amount,
            ],
        ];

        $this->data['instrument'] = [
            'token' => [
                'token' => $subscription->token,
            ],
        ];

        $this->data['returnUrl'] = route('payments.show', $payment);

        return $this;
    }

    public function process(): PaymentResponse
    {
        $url = $this->config['url'].'/api/session/';
        $response = Http::post($url, $this->data);
        $data = $response->json();
        Log::info('Processing payment with PlaceToPay', $data);
        return new PaymentResponse(
            $data['requestId'],
            $data['processUrl'],
            'success'
        );
    }

    public function processCollect(): Response
    {
        $url = $this->config['url'].'/api/collect/';
        $response = Http::post($url, $this->data);
        $data = $response->json();
        Log::info('Processing payment COLLECT with placeToPay', $data);

        return $response;
    }

    public function get(string $process_identifier): QueryPaymentResponse
    {

        $url = $this->config['url'].'/api/session/'.$process_identifier;

        $response = Http::post($url, $this->data);
        $response = $response->json();
        Log::info('Result of payment query with PlaceToPay', $response);
        $status = $response['status'];

        return new QueryPaymentResponse($status['reason'], $status['status']);
    }
}
