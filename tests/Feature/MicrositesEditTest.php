<?php

namespace Tests\Feature;

use App\Constants\PermissionSlug;
use App\Models\Category;
use App\Models\microsites;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class MicrositesEditTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanNotEditSiteWhenUserIsNotAuth(): void
    {
        $response = $this->get(route('microsites.edit', 1));
        $response->assertRedirect(route('login'));
    }

    public function testItCanEditSite(): void
    {
        $this->withoutExceptionHandling();
        $microsite = microsites::factory()
            ->for(Category::factory()->create())
            ->create(
                [
                    'name' => 'test-name',

                ]
            );
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_UPDATE]);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->get(route('microsites.edit', $microsite->id));
        $response->assertOk();
        $response->assertSee('test-name');
    }

    // public function testItCanNotEditSiteWhenUserHasNoPermission(): void
    // {
    //     $microsite = microsites::factory()
    //         ->for(Category::factory()->create())
    //         ->create(
    //             [
    //                 'name' => 'test-name',

    //             ]
    //         );
    //     $user = User::factory()->create();
    //     $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_VIEW]);
    //     $user->givePermissionTo($permission);

    //     $response = $this->actingAs($user)
    //         ->get(route('microsites.edit', $microsite->id));
    //     $response->assertForbidden();
    // }
}