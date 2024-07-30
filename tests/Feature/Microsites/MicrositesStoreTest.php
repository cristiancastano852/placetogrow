<?php

namespace Tests\Feature\Microsites;

use App\Constants\PermissionSlug;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class MicrositesStoreTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanNotSeeSiteCreateWhenUserIsNotAuth(): void
    {
        $response = $this->get(route('microsites.create'));
        $response->assertRedirect(route('login'));
    }

    public function testItCanSeeCreateFormSites(): void
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_CREATE->value]);
        $user->givePermissionTo($permission);
        $response = $this->actingAs($user)
            ->get(route('microsites.create'));
        $response->assertOk();
        $response->assertViewIs('microsites.create');
    }

    public function testItCanStoreSite(): void
    {

        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->make();
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_CREATE->value]);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->post(route('microsites.store'), $microsite->toArray());

        $response->assertRedirect(route('microsites.index'));

        $this->assertDatabaseHas('microsites', [
            'name' => $microsite->name,

        ]);
    }
}
