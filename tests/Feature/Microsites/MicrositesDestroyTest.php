<?php

namespace Tests\Feature\Microsites;

use App\Constants\PermissionSlug;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class MicrositesDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanNotSeeSiteDeleteWhenUserIsNotAuth(): void
    {

        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();

        $response = $this->get(route('microsites.show', $microsite));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testItCanDestroySite(): void
    {
        $this->withoutExceptionHandling();
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_DELETE]);

        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->delete(route('microsites.destroy', $microsite));

        $response->assertRedirect(route('microsites.index'));
    }
}
