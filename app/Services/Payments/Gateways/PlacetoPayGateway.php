<?php

namespace App\Services\Payments\Gateways;

use App\Contracts\PaymentGateway;
use App\Models\Payment;
use App\Services\Payments\PaymentResponse;
use App\Services\Payments\QueryPaymentResponse;
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

    public function process(): PaymentResponse
    {
        try {
            $response = Http::post($this->config['url'], $this->data);
            if ($response->successful()) {
                Log::info('PlacetoPay response succesful', $response->json());
                $data = $response->json();

                return new PaymentResponse(
                    $data['requestId'],
                    $data['processUrl'],
                    'success'
                );
            } elseif ($response->clientError()) {
                Log::error('PlacetoPay client error', $response->json());

                return new PaymentResponse(
                    0,
                    '',
                    'client_error',
                    $response->json('message', 'Client error occurred')
                );
            }
        } catch (\Exception $e) {
            Log::error('PlacetoPay exception', ['message' => $e->getMessage()]);

            return new PaymentResponse(0, '', 'exception', $message);
        }
    }

    public function get(Payment $payment): QueryPaymentResponse
    {
        $url = $this->config['url'].'/'.$payment->process_identifier;

        $response = Http::post($url, $this->data);
        $response = $response->json();

        $status = $response['status'];

        return new QueryPaymentResponse($status['reason'], $status['status']);
    }
}
