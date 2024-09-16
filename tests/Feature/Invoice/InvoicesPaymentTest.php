<?php

namespace Tests\Feature\Invoice;

use App\Models\Invoice;
use App\Models\Microsites;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InvoicesPaymentTest extends TestCase
{
    public function test_invoce_payment_success(): void
    {
        Http::fake([
            'https://checkout-co.placetopay.dev/api/session' => Http::response([
                'status' => [
                    'status' => 'OK',
                    'reason' => 'PC',
                    'message' => 'Session created successfully',
                    'date' => '2021-09-29T16:00:00-05:00',
                ],
                'requestId' => '123',
                'processUrl' => 'https://checkout-co.placetopay.dev/session/123456',
            ]),
        ]);
        $user = User::factory()->create();
        $microsite = Microsites::factory()->create();
        $this->actingAs($user);
        $invoice = Invoice::factory()->create(['microsite_id' => $microsite->id]);
        $invoice_id = $invoice->id;
        $response = $this->post(route('invoice.invoicesPayment', $microsite), ['invoice_id' => $invoice_id]);
        $response->assertStatus(302);
    }

    public function test_invoce_payment_exception(): void
    {
        Http::fake([
            'https://checkout-co.placetopay.dev/api/session' => Http::response([
                'status' => [
                    'status' => 'exception',
                    'reason' => 'PC',
                    'message' => 'Session created successfully',
                    'date' => '2021-09-29T16:00:00-05:00',
                ],
                'requestId' => '123',
                'processUrl' => 'https://checkout-co.placetopay.dev/session/123456',
            ]),
        ]);
        $user = User::factory()->create();
        $microsite = Microsites::factory()->create();
        $this->actingAs($user);
        $invoice = Invoice::factory()->create(['microsite_id' => $microsite->id]);
        $invoice_id = $invoice->id;
        $response = $this->post(route('invoice.invoicesPayment', $microsite), ['invoice_id' => $invoice_id]);
        $response->assertStatus(302);

    }
}
