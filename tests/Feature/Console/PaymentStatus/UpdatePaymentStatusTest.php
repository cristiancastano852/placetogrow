<?php

namespace Tests\Feature\Console\PaymentStatus;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UpdatePaymentStatusTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_update_payment_status_success(): void
    {
        $config = config('gateways.placetopay');
        $url = $config['url'].'/api/session/1';

        Http::fake(
            [
                $url => Http::response($this->collectPaymentResponse('APPROVED')),
            ]
        );

        Payment::factory()->create(
            [
                'status' => 'PENDING',
                'process_identifier' => '1',
            ]
        );

        $this->artisan('app:update-payment-status')
            ->assertExitCode(0);
    }

    private function collectPaymentResponse(string $status): array
    {
        return [
            'requestId' => 1,
            'status' => [
                'status' => 'APPROVED',
                'reason' => '00',
                'message' => 'La petición ha sido aprobada exitosamente',
                'date' => '2022-07-27T14:51:27-05:00',
            ],
            'request' => [
                'locale' => 'es_CO',
                'payer' => [
                    'document' => '1122334455',
                    'documentType' => 'CC',
                    'name' => 'John',
                    'surname' => 'Doe',
                    'company' => 'Evertec',
                    'email' => 'johndoe@app.com',
                    'mobile' => '+5731111111111',
                    'address' => [
                        'street' => 'Calle falsa 123',
                        'city' => 'Medellín',
                        'state' => 'Poblado',
                        'postalCode' => '55555',
                        'country' => 'Colombia',
                        'phone' => '+573111111111',
                    ],
                ],
                'buyer' => [
                    'document' => '1122334455',
                    'documentType' => 'CC',
                    'name' => 'John',
                    'surname' => 'Doe',
                    'company' => 'Evertec',
                    'email' => 'johndoe@app.com',
                    'mobile' => '+5731111111111',
                    'address' => [
                        'street' => 'Calle falsa 123',
                        'city' => 'Medellín',
                        'state' => 'Poblado',
                        'postalCode' => '55555',
                        'country' => 'Colombia',
                        'phone' => '+573111111111',
                    ],
                ],
                'payment' => [
                    'reference' => '12345',
                    'description' => 'Prueba de pago',
                    'amount' => [
                        'currency' => 'COP',
                        'total' => 2000,
                        'taxes' => [
                            ['kind' => 'valueAddedTax', 'amount' => 1000, 'base' => 0],
                        ],
                        'details' => [['kind' => 'discount', 'amount' => 1000]],
                    ],
                    'allowPartial' => false,
                    'shipping' => [
                        'document' => '1122334455',
                        'documentType' => 'CC',
                        'name' => 'John',
                        'surname' => 'Doe',
                        'company' => 'Evertec',
                        'email' => 'johndoe@app.com',
                        'mobile' => '+5731111111111',
                        'address' => [
                            'street' => 'Calle falsa 123',
                            'city' => 'Medellín',
                            'state' => 'Poblado',
                            'postalCode' => '55555',
                            'country' => 'Colombia',
                            'phone' => '+573111111111',
                        ],
                    ],
                    'items' => [
                        [
                            'sku' => '12345',
                            'name' => 'product_1',
                            'category' => 'physical',
                            'qty' => '1',
                            'price' => 1000,
                            'tax' => 0,
                        ],
                    ],
                    'fields' => [
                        [
                            'keyword' => '_test_field_value_',
                            'value' => '_test_field_',
                            'displayOn' => 'approved',
                        ],
                    ],
                    'recurring' => [
                        'periodicity' => 'D',
                        'interval' => '1',
                        'nextPayment' => '2019-08-24',
                        'maxPeriods' => 1,
                        'dueDate ' => '2019-09-24',
                        'notificationUrl ' => 'https://checkout.placetopay.com',
                    ],
                    'subscribe' => false,
                    'dispersion' => [
                        [
                            'agreement' => '1299',
                            'agreementType' => 'MERCHANT',
                            'amount' => ['currency' => 'USD', 'total' => 200],
                        ],
                    ],
                    'modifiers' => [
                        [
                            'type' => 'FEDERAL_GOVERNMENT',
                            'code' => 17934,
                            'additional' => ['invoice' => '123345'],
                        ],
                    ],
                ],
                'subscription' => [
                    'reference' => '12345',
                    'description' => 'Ejemplo de descripción',
                    'fields' => [
                        'keyword' => '1111',
                        'value' => 'lastDigits',
                        'displayOn' => 'none',
                    ],
                ],
                'fields' => [
                    [
                        'keyword' => '_processUrl_',
                        'value' => 'https://checkout.redirection.test/session/1/a592098e22acc709ec7eb30fc0973060',
                        'displayOn' => 'none',
                    ],
                ],
                'paymentMethod' => 'visa',
                'expiration' => '2019-08-24T14:15:22Z',
                'returnUrl' => 'https://commerce.test/return',
                'cancelUrl' => 'https://commerce.test/cancel',
                'ipAddress' => '127.0.0.1',
                'userAgent' => 'PlacetoPay Sandbox',
                'skipResult' => false,
                'noBuyerFill' => false,
                'type' => 'checkin',
            ],
            'payment' => [
                [
                    'status' => [
                        'status' => 'APPROVED',
                        'reason' => '00',
                        'message' => 'La petición ha sido aprobada exitosamente',
                        'date' => '2022-07-27T14:51:27-05:00',
                    ],
                    'internalReference' => 12345,
                    'reference' => '12345',
                    'paymentMethod' => 'visa',
                    'paymentMethodName' => 'Visa',
                    'issuerName' => 'JPMORGAN CHASE BANK, N.A.',
                    'amount' => [
                        'from' => ['currency ' => 'COP', 'total ' => 10000],
                        'to' => ['currency ' => 'COP', 'total ' => 10000],
                        'factor' => 1,
                    ],
                    'receipt' => '052617800175',
                    'franchise' => 'PS_VS',
                    'refunded' => false,
                    'authorization' => '965960',
                    'processorFields' => [
                        [
                            'keyword' => '1111',
                            'value' => 'lastDigits',
                            'displayOn' => 'none',
                        ],
                    ],
                    'dispersion' => null,
                    'agreement' => null,
                    'agreementType' => null,
                    'discount' => [
                        'base' => 3000,
                        'code' => '17934',
                        'type' => 'FRANCHISE',
                        'amount' => 1000,
                    ],
                    'subscription' => null,
                ],
            ],
            'subscription' => [
                'status' => [
                    'status' => 'OK',
                    'reason' => '00',
                    'message' => 'La petición ha sido aprobada exitosamente',
                    'date' => '2022-07-27T14:51:27-05:00',
                ],
                'type' => 'token',
                'instrument' => [
                    [
                        'keyword' => 'token',
                        'value' => 'a3bfc8e2afb9ac5583922eccd6d2061c1b0592b099f04e352a894f37ae51cf1a',
                        'displayOn' => 'none',
                    ],
                    [
                        'keyword' => 'subtoken',
                        'value' => '8740257204881111',
                        'displayOn' => 'none',
                    ],
                    [
                        'keyword' => 'franchise',
                        'value' => 'visa',
                        'displayOn' => 'none',
                    ],
                    [
                        'keyword' => 'franchiseName',
                        'value' => 'Visa',
                        'displayOn' => 'none',
                    ],
                    [
                        'keyword' => 'issuerName',
                        'value' => 'JPMORGAN CHASE BANK, N.A.',
                        'displayOn' => 'none',
                    ],
                    [
                        'keyword' => 'lastDigits',
                        'value' => '1111',
                        'displayOn' => 'none',
                    ],
                    [
                        'keyword' => 'validUntil',
                        'value' => '2029-12-31',
                        'displayOn' => 'none',
                    ],
                    [
                        'keyword' => 'installments',
                        'value' => null,
                        'displayOn' => 'none',
                    ],
                ],
            ],
        ];
    }
}
