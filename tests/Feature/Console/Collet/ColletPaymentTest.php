<?php

namespace Tests\Feature\Collet;

use App\Constants\SubscriptionStatus;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ColletPaymentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_collect_no_subscription(): void
    {

        $this->artisan('app:collect')
            ->assertExitCode(0);
    }

    public function test_collent_payment_success(): void
    {
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();
        $user = User::factory()->create();
        $plan = Plan::factory()->create(
            [
                'microsite_id' => $microsite->id,
            ]
        );
        $config = config('gateways.placetopay');
        Http::fake(
            [
                $config['url'].'/api/collect/' => Http::response($this->collectPaymentResponse('APPROVED')),
            ]
        );

        Subscription::factory()->create(
            [
                'user_id' => $user->id,
                'microsite_id' => $microsite->id,
                'plan_id' => $plan->id,
                'status' => SubscriptionStatus::ACTIVE->value,
                'next_billing_date' => Carbon::now()->addDay(),
                'expiration_date' => Carbon::now()->addMonths(12),
            ]
        );
        $this->artisan('app:collect')
            ->assertExitCode(0);
    }

    public function test_collent_payment_failed(): void
    {
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();
        $user = User::factory()->create();
        $plan = Plan::factory()->create(
            [
                'microsite_id' => $microsite->id,
            ]
        );
        $config = config('gateways.placetopay');
        Http::fake(
            [
                $config['url'].'/api/collect/' => Http::response($this->collectPaymentResponse('REJECTED')),
            ]
        );

        Subscription::factory()->create(
            [
                'user_id' => $user->id,
                'microsite_id' => $microsite->id,
                'plan_id' => $plan->id,
                'status' => SubscriptionStatus::ACTIVE->value,
                'next_billing_date' => Carbon::now(),
                'expiration_date' => Carbon::now()->addMonths(12),
            ]
        );
        $this->artisan('app:collect')
            ->assertExitCode(0);
    }

    private function collectPaymentResponse(string $status): array
    {
        return [
            'requestId' => 1,
            'status' => [
                'status' => $status,
                'reason' => '00',
                'message' => 'La petición ha sido aprobada exitosamente',
                'date' => '2021-11-30T15:49:47-05:00',
            ],
            'request' => [
                'locale' => 'es_CO',
                'payer' => [
                    'document' => '1033332222',
                    'documentType' => 'CC',
                    'name' => 'Name',
                    'surname' => 'LastName',
                    'email' => 'dnetix1@app.com',
                    'mobile' => '3111111111',
                    'address' => ['postalCode' => '12345'],
                ],
                'payment' => [
                    'reference' => '1122334455',
                    'description' => 'Prueba',
                    'amount' => ['currency' => 'USD', 'total' => 100],
                    'allowPartial' => false,
                    'subscribe' => false,
                ],
                'returnUrl' => 'https://redirection.test/home',
                'ipAddress' => '127.0.0.1',
                'userAgent' => 'PlacetoPay Sandbox',
                'expiration' => '2021-12-30T00:00:00-05:00',
            ],
            'payment' => [
                [
                    'status' => [
                        'status' => 'APPROVED',
                        'reason' => '00',
                        'message' => 'Aprobada',
                        'date' => '2021-11-30T15:49:36-05:00',
                    ],
                    'internalReference' => 1,
                    'paymentMethod' => 'visa',
                    'paymentMethodName' => 'Visa',
                    'issuerName' => 'JPMORGAN CHASE BANK, N.A.',
                    'amount' => [
                        'from' => ['currency' => 'USD', 'total' => 100],
                        'to' => ['currency' => 'USD', 'total' => 100],
                        'factor' => 1,
                    ],
                    'authorization' => '000000',
                    'reference' => '1122334455',
                    'receipt' => '241516',
                    'franchise' => 'DF_VS',
                    'refunded' => false,
                    'processorFields' => [
                        [
                            'keyword' => 'lastDigits',
                            'value' => '1111',
                            'displayOn' => 'none',
                        ],
                    ],
                ],
            ],
            'subscription' => null,
        ];
    }
}
