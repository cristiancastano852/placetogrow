<?php

namespace App\Console\Commands;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Models\Payment;
use App\Models\Subscription;
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
        $PaymentsPending = Payment::where('status', PaymentStatus::PENDING->value)
            ->get();
        Log::info('---- Updating payment status   ----');
        foreach ($PaymentsPending as $payment) {
            /** @var PaymentService $paymentService */
            $paymentService = app(PaymentService::class, [
                'payment' => $payment,
                'gateway' => $payment->gateway,
            ]);
            Log::info(' Update payment status'.$payment->id.'sss'.$payment->reference);
            if ($payment->status === PaymentStatus::PENDING->value) {
                $payment = $paymentService->query();
                $suscription_id = $payment->suscription_id;
                $subscription = Subscription::find($suscription_id);
                if ($payment->status === PaymentStatus::PENDING->value) {
                    continue;
                }
                if ($payment->status === PaymentStatus::APPROVED->value and $subscription->next_payment_date->lessThanOrEqualTo(now())) {
                    $this->log('Payment APPROVED'.$payment->id);
                    $subscription->next_payment_date = $subscription->next_payment_date->addMonth();
                }
                if ($payment->status === PaymentStatus::REJECTED->value and $subscription->next_payment_date->lessThanOrEqualTo(now())) {
                    $this->log('Payment rejected'.$payment->id);
                    // $subscription->status = PaymentStatus::REJECTED->value;
                }
            }
        }
    }
}
