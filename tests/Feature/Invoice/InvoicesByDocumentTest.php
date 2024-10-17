<?php

namespace Tests\Feature\Invoice;

use App\Models\Invoice;
use App\Models\Microsites;
use App\Models\User;
use Tests\TestCase;

class InvoicesByDocumentTest extends TestCase
{
    public function test_invoice_by_document_success(): void
    {
        $user = User::factory()->create();
        $microsite = Microsites::factory()->create();
        $this->actingAs($user);
        $invoice = Invoice::factory()->create(['microsite_id' => $microsite->id]);
        $document_number = $invoice->document_number;
        $response = $this->post(route('invoice.invoicesByDocument', $microsite, ['document_number' => $document_number]));
        $response->assertStatus(200);
    }
}
