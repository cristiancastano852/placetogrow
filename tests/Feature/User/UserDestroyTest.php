<?php

namespace Tests\Feature\User;

use App\Constants\PermissionSlug;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanNotSeeUsersDeleteWhenUserIsNotAuth(): void
    {
        $category = Category::factory()->create();
        $response = $this->get(route('users.show', $category));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
       
    }

    public function testItCanDestroyUserWhenUserIsAuthAndHavePermissions(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::USERS_DELETE]);
        $user->givePermissionTo($permission);

        $userToDelete = User::factory()->create();
        $response = $this->actingAs($user)
            ->delete(route('users.destroy', $userToDelete));

        $response->assertRedirect(route('users.index'));
    }
}
