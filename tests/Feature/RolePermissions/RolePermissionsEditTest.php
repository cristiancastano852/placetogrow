<?php

namespace Tests\Feature\RolePermissions;

use App\Constants\PermissionSlug;
use App\Models\Role as ModelsRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RolePermissionsEditTest extends TestCase
{
    use RefreshDatabase;

    public function testCanViewRolePermissionsEditWhenUserIsAuth()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::ROLE_PERMISSION_UPDATE]);
        $user->givePermissionTo($permission);
        $role = ModelsRole::factory()->create();

        $response = $this->actingAs($user)
            ->put(route('admin.rolePermission.edit-permissions', $role), [
                'permissions' => ['permission1', 'permission2']
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Permisos actualizados correctamente.');
    }

    public function testCanNotEditPermissionsWhenUserIsNotAuth()
    {
        $role = ModelsRole::factory()->create();

        $response = $this->put(route('admin.rolePermission.edit-permissions', $role), [
            'permissions' => ['permission1', 'permission2']
        ]);

        $response->assertRedirect(route('login'));
    }
}

