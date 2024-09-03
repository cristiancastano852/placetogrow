<?php

namespace Tests\Feature\Feature\Plan\Store;

use App\Constants\PermissionSlug;
use App\Models\Microsites;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PlanStoreTest extends TestCase
{
    public function test_create_plan_no_auth(): void
    {
        $microsite = Microsites::factory()->create();

        $response = $this->get(route('plans.create', $microsite->id));
        $response->assertRedirect(route('login'));
    }

    public function test_create_plan_auth(): void
    {
        $user = User::factory()->create();
        $microsite = Microsites::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('plans.create', $microsite->id));
        $response->assertOk();
    }

    public function test_store_plan_successfully(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_CREATE->value]);
        $user->givePermissionTo($permission);
        $microsite = Microsites::factory()->create();

        $this->actingAs($user);

        $data = [
            'plans' => [
                [
                    'name' => 'Basic Plan',
                    'price' => 10,
                    'description' => 'This is a basic plan.',
                    'duration_unit' => 'month',
                    'billing_frequency' => 1,
                    'duration_period' => 12,
                ],
            ],
        ];

        $response = $this->actingAs($user)
            ->get(route('microsites.create'));
        $response = $this->post(route('plans.store', $microsite->id), $data);

        $response->assertRedirect(route('microsites.show', $microsite->id));
        $response->assertSessionHas('success', 'Planes creados correctamente');

        $this->assertDatabaseHas('plans', [
            'microsite_id' => $microsite->id,
            'name' => 'Basic Plan',
            'price' => 10,
            'description' => 'This is a basic plan.',
            'duration_unit' => 'month',
            'billing_frequency' => 1,
            'duration_period' => 12,
        ]);
    }
}
