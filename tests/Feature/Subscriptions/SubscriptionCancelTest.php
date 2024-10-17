<?php

namespace Tests\Feature\Subscriptions;

use App\Constants\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubscriptionCancelTest extends TestCase
{
    public function test_cancel_subcription(): void
    {
        $config = config('gateways.placetopay');
        $url = $config['url'].'/api/instrument/invalidate';
        Http::fake([
            $url => Http::response([
                'status' => [
                    'status' => 'APPROVED',
                    'reason' => 'PC',
                    'message' => 'Instrument invalidated successfully',
                    'date' => '2021-09-29T16:00:00-05:00',
                ],
            ]),
        ]);

        $user = User::factory()->create();
        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'status' => SubscriptionStatus::ACTIVE->value,
            'token' => Crypt::encryptString('123'),
            'subtoken' => Crypt::encryptString('123'),
        ]);

        $response = $this->actingAs($user)
            ->get(route('subscriptions.cancel', $subscription->id));
        $response->assertStatus(302);

    }
}
