<?php

namespace Tests\Feature\User;

use App\Constants\PermissionSlug;
use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserStoreTest extends TestCase
{
    public function testItCanNotSeeUsersCreateWhenUserIsNotAuth()
    {

        $response = $this->get(route('users.create'));
        $response->assertRedirect(route('login'));
    }

    public function testItCanSeeUsersCreateWhenUserIsAuth()
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::USERS_CREATE]);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->get(route('users.create'));
        $response->assertOk();
    }

    public function testItCanStoreUserIfHavePermissions()
    {
        $role = RoleFactory::new()->create(['name' => 'admin']);
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::USERS_CREATE]);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->post(route('users.store'), [
                'name' => 'test-name',
                'email' => 'testemail@a.com',
                'password' => 'test-password',
                'password_confirmation' => 'test-password',
                'role' => $role->id

            ]);
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['name' => 'test-name']);
        
    }

    public function testItCanNotStoreUserIfHaveNoPermissions()
    {
        $role = RoleFactory::new()->create(['name' => 'admin2']);
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::USERS_VIEW_ANY]);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->post(route('users.store'), [
                'name' => 'test-name',
                'email' => 'test-email',
                'password' => 'test-password',
                'password_confirmation' => 'test-password',
                'role' => $role->id
            ]);
        $response->assertForbidden();
        }
}
