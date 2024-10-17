<?php

namespace App\Jobs;

use App\Constants\InvoiceStatus;
use App\Mail\InvoiceDueAlertMail;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceDueAlertJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct() {}

    public function handle(): void
    {
        Log::info('Sending invoice due alert mail');
        $today = Carbon::now();
        $expiryDate = $today->copy()->addDays(7)->format('Y-m-d');
        $invoices = Invoice::where('expiration_date', $expiryDate)
            ->where('status', InvoiceStatus::PENDING->name)
            ->with(['microsite'])
            ->get();
        if ($invoices->isEmpty()) {
            Log::info("No invoices found for due alert mail: {$expiryDate}");

            return;
        }

        foreach ($invoices as $invoice) {
            Log::info("Sending invoice due alert mail to: {$invoice->email}");
            Mail::to($invoice->email)->send(new InvoiceDueAlertMail($invoice));
        }
    }
}
