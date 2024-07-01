<?php

namespace Tests\Feature;

use App\Constants\PermissionSlug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as ModelsRole;
use Tests\TestCase;

class RoleUpdateTest extends TestCase
{
    use RefreshDatabase;
    
    public function testAdminCanAssignRolesToUser()
    {
        $this->withoutExceptionHandling();
        $admin = User::factory()->create();
        $roleAdmin = ModelsRole::create(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->assignRole($roleAdmin);
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::ROLE_PERMISSION_UPDATE]);
        $admin->givePermissionTo($permission);
        
        $user = User::factory()->create();

        $role = ModelsRole::create(['name' => 'client', 'guard_name' => 'web']);

        $response = $this->actingAs($admin)
            ->put(route('admin.users.update', $user->id), ['role' => $role->id]);


        $response->assertRedirect(route('users.index'));
        $this->assertTrue($user->fresh()->hasRole('client'));
    }
}
