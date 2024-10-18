<?php

namespace Tests\Feature\Jobs;

use App\Constants\InvoiceStatus;
use App\Jobs\ProcessExpiredPendingInvoiceJob;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsites;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessExpiredPendingInvoiceJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_expired_pending_invoice_job_success(): void
    {
        Microsites::factory()
            ->for(Category::factory()->create())
            ->create();

        $invoice = Invoice::factory()->create([
            'status' => InvoiceStatus::PENDING->name,
            'expiration_date' => now()->subDay(),
        ]);

        $job = new ProcessExpiredPendingInvoiceJob($invoice);
        $job->handle();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::EXPIRED->name,
        ]);

    }
}
