<?php

namespace Tests\Feature\RolePermissions;

use App\Constants\PermissionSlug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RolePermissionsIndexTest extends TestCase
{
    public function testCanItNotViewWhenUserIsNotAuth()
    {
        $response = $this->get(route('rolePermission.index'));
        $response->assertRedirect(route('login'));
    }

    public function testCanViewRolePermissionsIndex()
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::ROLE_PERMISSION_VIEW]);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->get(route('rolePermission.index'));
        $response->assertOk();
    }
}
