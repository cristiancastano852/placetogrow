<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Microsites;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubscriptionReturnTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_return_subcription_and_save_token(): void
    {

        Http::fake([
            'https://checkout-co.placetopay.dev/api/session/1' => Http::response(
                $this->getFakeResponseSession(),
            ),
        ]);

        $user = User::factory()->create();
        $microsite = Microsites::factory()->create();
        $suscription = Subscription::factory()->create([
            'request_id' => '1',
        ]);

        $response = $this->actingAs($user)
            ->get(route('subscriptions.return', [
                'subscription' => $suscription->id,
                'microsite' => $microsite->id,
            ]));
        $response->assertStatus(302);

    }

    private function getFakeResponseSession(): array
    {
        return [
            'requestId' => 1,
            'status' => [
                'status' => 'APPROVED',
                'reason' => '00',
                'message' => 'La petición ha sido aprobada exitosamente',
                'date' => '2022-07-27T14:51:27-05:00',
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
