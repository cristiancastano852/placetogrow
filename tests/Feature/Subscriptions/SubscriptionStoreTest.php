<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Microsites;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubscriptionStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_store_subcription(): void
    {

        Http::fake([
            'https://checkout-co.placetopay.dev/api/session/' => Http::response([
                'status' => [
                    'status' => 'OK',
                    'reason' => 'PC',
                    'message' => 'Session created successfully',
                    'date' => '2021-09-29T16:00:00-05:00',
                ],
                'requestId' => '123',
                'processUrl' => 'https://checkout-co.placetopay.dev/session/123456',
            ]),
        ]);
        $user = User::factory()->create();
        $microsite = Microsites::factory()->create();
        $plan = Plan::factory()->create();

        $data = [
            'plan_id' => $plan->id,
        ];
        $response = $this->actingAs($user)
            ->post(route('subscriptions.store', $microsite->id), $data);

        $response->assertStatus(302);
    }
}
