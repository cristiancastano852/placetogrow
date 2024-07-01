<?php

namespace Tests\Feature\Microsites;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Constants\PermissionSlug;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;
use App\Models\Microsites;
use Spatie\Permission\Models\Permission;

class MicrositesUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanNotSeeSiteUpdateWhenUserIsNotAuth(): void
    {
        $category = Category::factory()->create();
        $microsite = Microsites::factory()
            ->for($category)
            ->create();
        $response = $this->put(route('microsites.update', $microsite, $category));
        $response->assertRedirect(route('login'));
    }


    public function testItCanUpdateSite(): void
    {
        $this->withoutExceptionHandling();
        //The slug field is required.
        $category = Category::factory()->create();

        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => PermissionSlug::MICROSITES_UPDATE]);
        $user->givePermissionTo($permission);

        $microsite = Microsites::factory()
            ->for($category)
            ->for($user)
            ->create(
                [
                    'name' => 'test-name',
                    'slug' => 'test-slug',
                    'category_id' => $category->id,
                    'document_type' => DocumentTypes::CC->name,
                    'document_number' => '123456789',
                    'logo' => 'test-logo',
                    'currency' => Currency::COP->name,
                    'site_type' => MicrositesTypes::Donaciones->name,
                    'payment_expiration' => 10,
                ]
            );

        $response = $this->actingAs($user)
            ->put(route('microsites.update', $microsite->id), [
                'id' => $microsite->id,
                'name' => 'test-name-updated',
                'slug' => 'test-slug-updated',
                'document_type' => DocumentTypes::CC->name,
                'document_number' => '123456789',
                'category_id' => $category->id,
                'logo' => 'test-logo',
                'currency' => Currency::COP->name,
                'site_type' => MicrositesTypes::Donaciones->name,
                'payment_expiration' => 10,
            ]);

        $response->assertRedirect(route('microsites.index'));
        $response->assertSessionHas('success', 'Sitio actualizado correctamente.');
    }
}
