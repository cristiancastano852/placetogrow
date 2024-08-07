<?php

namespace Tests\Feature\User;

use App\Constants\PermissionSlug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanNotListUsersWhenUserIsNotAuth(): void
    {
        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));

    }

    public function testItCanListUsers(): void
    {

        User::factory()->create(
            [
                'name' => 'test-name',
                'email' => 'test-email',
            ]
        );

        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::USERS_VIEW_ANY->value]);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->get(route('users.index'));
        $response->assertOk();
        $response->assertSee('test-name');
    }
}
