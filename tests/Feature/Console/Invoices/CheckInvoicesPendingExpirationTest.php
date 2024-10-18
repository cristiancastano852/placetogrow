<?php

namespace Tests\Feature\Console\Invoices;

use App\Constants\InvoiceStatus;
use App\Jobs\ProcessExpiredPendingInvoiceJob;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsites;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CheckInvoicesPendingExpirationTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_invoices_pending_expiration_no_invoices(): void
    {
        $this->artisan('app:check-invoices-pending-expiration')
            ->assertExitCode(0);
    }

    public function test_check_invoices_pending_expiration_with_invoices(): void
    {
        Bus::fake();


        Microsites::factory()
            ->for(Category::factory()->create())
            ->create();
        
        Invoice::factory()->create([
            'status' => InvoiceStatus::PENDING->name,
            'expiration_date' => now()->subDay(),
        ]);

        $this->artisan('app:check-invoices-pending-expiration')
            ->assertExitCode(0);

        Bus::assertDispatched(ProcessExpiredPendingInvoiceJob::class);
        
    }
}
