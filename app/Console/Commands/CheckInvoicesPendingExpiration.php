<?php

namespace App\Console\Commands;

use App\Constants\InvoiceStatus;
use App\Jobs\ProcessExpiredPendingInvoiceJob;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckInvoicesPendingExpiration extends Command
{
    protected $signature = 'app:check-invoices-pending-expiration';

    protected $description = 'Check invoices pending expiration';

    public function handle()
    {
        Log::info('Checking invoices pending expiration');
        $invoicesPendingExpiration = Invoice::where('status', InvoiceStatus::PENDING->name)
            ->where('expiration_date', '<', now())
            ->with('microsite:id,late_fee_percentage')
            ->get(['id', 'microsite_id', 'expiration_date', 'amount', 'Email']);

        if ($invoicesPendingExpiration->isEmpty()) {
            Log::info('No invoices pending expiration');

            return;
        }

        foreach ($invoicesPendingExpiration as $invoice) {
            ProcessExpiredPendingInvoiceJob::dispatch($invoice);

        }
    }
}
