<?php

namespace Tests\Feature\Feature\Plan\Store;

use App\Constants\PermissionSlug;
use App\Models\Microsites;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PlanIndexTest extends TestCase
{
    public function test_index_plan(): void
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_CREATE->value]);

        $user->givePermissionTo($permission);

        $microsite = Microsites::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('plans.index', $microsite->id));
        $response->assertOk();

    }
}
