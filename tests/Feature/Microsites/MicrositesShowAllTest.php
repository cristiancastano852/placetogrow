<?php

namespace Tests\Feature\Microsites;

use App\Models\User;
use Tests\TestCase;

class MicrositesShowAllTest extends TestCase
{
    public function testItCanSeeAllMicrosites(): void
    {

        $response = $this->get(route('micrositesall'));
        $response->assertOk();
    }

    public function testItCanSeeAllMicrositesWhenUserIsAuth(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get(route('micrositesall'));
        $response->assertOk();
        $response->assertSee('Microsites');
    }
}
