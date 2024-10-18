<?php

namespace App\Console\Commands;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdatePaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-payment-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payment status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('---- Updating payment status   ----');
        $PaymentsPending = Payment::where('status', PaymentStatus::PENDING->value)
            ->Where('process_identifier', '!=', null)
            ->get();

        if ($PaymentsPending->isEmpty()) {
            Log::info('No payments to update');

            return;
        }
        foreach ($PaymentsPending as $payment) {
            Log::info('Check payment with id '.$payment->id);

            /** @var PaymentService $paymentService */
            $paymentService = app(PaymentService::class, [
                'payment' => $payment,
                'gateway' => $payment->gateway,
            ]);
            if ($payment->status === PaymentStatus::PENDING->value) {
                $payment = $paymentService->query();
            }
            Log::info('---- Finished updating payment status to '.$payment->status->value.' ----');
        }
    }
}
