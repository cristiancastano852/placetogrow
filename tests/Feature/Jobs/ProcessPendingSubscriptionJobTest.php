<?php

namespace Tests\Feature\Jobs;

use App\Constants\SubscriptionStatus;
use App\Jobs\ProcessPendingSubscriptionJob;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProcessPendingSubscriptionJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2024-10-15'));
    }

    public function test_process_pending_subscription_job_success(): void
    {
        $config = config('gateways.placetopay');
        Http::fake([
            $config['url'].'/api/session/1' => Http::response(
                $this->getFakeResponseSessionSubs(),
            ),
        ]);
        Http::fake([
            $config['url'].'/api/collect/' => Http::response(
                $this->collectPaymentResponse('APPROVED'),
            ),
        ]);

        $user = User::factory()->create();

        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();
        $this->actingAs($user);

        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'microsite_id' => $microsite->id,
            'status' => SubscriptionStatus::PENDING->value,
            'request_id' => '1',
            'next_billing_date' => Carbon::now(),
            'expiration_date' => Carbon::now()->addMonths(12),
            'next_retry_date' => Carbon::now(),
            'retry_attempts' => 0,
        ]);

        $job = new ProcessPendingSubscriptionJob($subscription);
        $job->handle();

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => SubscriptionStatus::ACTIVE->value,
        ]);
    }

    private function getFakeResponseSessionSubs(): array
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
