<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Microsites;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class SubscriptionIndexTest extends TestCase
{
    public function test_index_subcription(): void
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();
        $user->assignRole($role);
        $microsite = Microsites::factory()->create();
        $plan = Plan::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('subscriptions.index'));
        $response->assertStatus(200);

    }
}
