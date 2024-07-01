<?php

namespace Tests\Feature\RolePermissions;

use App\Constants\PermissionSlug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RolePermissionsManageTest extends TestCase
{
    public function testCanViewRolePermissionsManage()
    {
        $response = $this->get(route('rolePermission.permissions'));
        $response->assertRedirect(route('login'));
    }

    public function testCanViewRolePermissionsManageWhenUserIsAuth()
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::ROLE_PERMISSION_VIEW]);
        $user->givePermissionTo($permission);
        
        $response = $this->actingAs($user)
            ->get(route('rolePermission.permissions'));
        $response->assertOk();
    }

    public function testCanViewRolePermissionsManageWhenUserIsAuthButDoesNotHavePermission()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get(route('rolePermission.permissions'));
        $response->assertForbidden();
    }
}
