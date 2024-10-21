<?php

namespace Tests\Feature\Plan\Update;

use App\Constants\PermissionSlug;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PlanUpdateTest extends TestCase
{
    public function test_plan_edit_successfully(): void
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_CREATE->value]);
        $user->givePermissionTo($permission);
        $microsite = Microsites::factory()->create();

        $this->actingAs($user);

        $plan = Plan::factory()->create();
        $response = $this->get(route('plans.edit', [$microsite->id, $plan->id]));

        $response->assertOk();
    }

    public function test_plan_update_successfully(): void
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_CREATE->value]);
        $user->givePermissionTo($permission);
        $microsite = Microsites::factory()->create();

        $this->actingAs($user);

        $plan = Plan::factory()->create();
        $data = [
            'plan' => [
                'name' => 'Basic Plan2',
                'price' => 10,
                'description' => 'This is a basic plan.',
                'duration_unit' => 'Months',
                'billing_frequency' => 1,
                'duration_period' => 12,
            ],
        ];
        $response = $this->put(route('plans.update', [$microsite->id, $plan->id]), $data);

        $response->assertRedirect(route('plans.index', $microsite->id));

    }
}
