<?php

namespace Tests\Feature\Invoice;

use App\Models\Microsites;
use App\Models\User;
use Tests\TestCase;

class InvoiceIndexTest extends TestCase
{
    public function test_invoice_index_success(): void
    {
        $user = User::factory()->create();
        $microsite = Microsites::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('invoice.index', $microsite));

        $response->assertStatus(200);

    }
}
