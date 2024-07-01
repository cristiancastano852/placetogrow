<?php

namespace Tests\Feature\Microsites;

use App\Constants\PermissionSlug;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_UPDATE]);
        $user->givePermissionTo($permission);

        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->for(($user))
            ->create(
                [
                    'name' => 'test-name',

                ]
            );

        $response = $this->actingAs($user)
            ->get(route('microsites.edit', $microsite->id));
        $response->assertOk();
        $response->assertSee('test-name');
    }
}
