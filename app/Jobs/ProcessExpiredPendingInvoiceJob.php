<?php

namespace App\Jobs;

use App\Constants\InvoiceStatus;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessExpiredPendingInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function handle(): void
    {
        $invoice = $this->invoice;
        $late_fee_percentage = $invoice->microsite->late_fee_percentage;
        $lateFee = $invoice->amount * $late_fee_percentage / 100;
        $invoice->late_fee_amount = $lateFee;
        $invoice->total_amount = $invoice->amount + $lateFee;
        $invoice->status = InvoiceStatus::EXPIRED->name;
        $invoice->save();
        Log::info('Invoice expired', ['invoice' => $invoice]);
    }
}
