<?php

namespace Tests\Feature\Invoice;

use App\Models\Microsites;
use App\Models\User;
use Tests\TestCase;

class InvoicesByMicrositeTest extends TestCase
{
    public function test_invoice_by_microsite_success(): void
    {
        $user = User::factory()->create();
        $microsite = Microsites::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('invoice.invoicesByMicrosite', $microsite));
        $response->assertStatus(200);

    }
}
