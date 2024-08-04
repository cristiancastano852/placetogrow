<?php

namespace Tests\Feature\Microsites;

use App\Models\Category;
use App\Models\Microsites;
use App\Models\User;
use Tests\TestCase;

class MicrositesShowSpecificTest extends TestCase
{
    public function testItCanSeeSiteShowWhenUserIsNotAuth(): void
    {

        $user = User::factory()->create();
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->for(($user))
            ->create(
                [
                    'name' => 'test-name',

                ]
            );
        $id = $microsite->id;
        $slug = $microsite->slug;
        $response = $this->actingAs($user)->get(route('microsite.showMicrosite', ['slug' => $slug, 'id' => $id]));
        $response->assertStatus(200);
    }
}
