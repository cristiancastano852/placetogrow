<?php

namespace Tests\Feature\Feature\Plan\Show;

use App\Constants\PermissionSlug;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PlanShowTest extends TestCase
{
    public function test_show_plans_success(): void
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_CREATE->value]);

        $user->givePermissionTo($permission);

        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();

        $response = $this->actingAs($user)
            ->get(route('plans.show', $microsite->id));

        $response->assertOk();

    }
}
