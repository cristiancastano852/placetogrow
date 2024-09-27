<?php

namespace Tests\Feature\Plan\Destroy;

use App\Constants\PermissionSlug;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PlanDestroyTest extends TestCase
{

    public function test_plan_destroy_success(): void
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_CREATE->value]);
        $user->givePermissionTo($permission);
        $microsite = Microsites::factory()->create();

        $this->actingAs($user);

        $plan = Plan::factory()->create();
        $response = $this->delete(route('plans.destroy', [$microsite->id, $plan->id]));

        $response->assertRedirect(route('plans.index', $microsite->id));

    }
}
