<?php

namespace App\Console\Commands;

use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Payments\SubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Collect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle()
    {
        $this->info('---Collecting payment for subscription');
        Log::info('Collecting payment for subscription');
        $subscriptionsToCollect = Subscription::where('status', SubscriptionStatus::ACTIVE->value)
            ->where('next_retry_date', '<=', now())
            ->with('microsite')
            ->get();

        if ($subscriptionsToCollect->isEmpty()) {
            $this->info('---No subscription to collect');
            Log::info('No subscription to collect');

            return;
        }

        foreach ($subscriptionsToCollect as $subscription) {
            $this->processPayment($subscription);
        }

    }

    private function processPayment(Subscription $subscription)
    {
        Log::info('Processing payment for subscription '.$subscription->id);

        $microsite = Microsites::find($subscription->microsite_id);
        $subscriptionService = new SubscriptionService($microsite->payment_expiration, $subscription);
        $response = $subscriptionService->ProcessPaymentCollect();
        if ($response['status']['status'] === PaymentStatus::APPROVED->value) {
            Log::info('Payment approved of subscription: '.$subscription->id);
            $billing_frequency = $subscription->billing_frequency;
            $plan = Plan::find($subscription->plan_id);
            $duration_unit = $plan->duration_unit;
            $new_next_billing_date = $subscription->next_billing_date->add($duration_unit, $billing_frequency);

            if ($new_next_billing_date->greaterThan($subscription->expiration_date)) {
                $subscription->status = SubscriptionStatus::EXPIRED->value;
            }
            $subscription->next_retry_date = $new_next_billing_date;
            $subscription->next_billing_date = $new_next_billing_date;
            $subscription->retry_attempts = 0;
            $subscription->save();

            return;
        }

        if ($subscription->retry_attempts >= $microsite->payment_retries) {
            Log::info('Payment rejected, subscription suspended '.$subscription->id);
            $subscription->status = SubscriptionStatus::SUSPENDED->value;
            $subscription->save();

            //Send Email to user that subscription is suspended
            return;
        }
        $subscription->retry_attempts = $subscription->retry_attempts + 1;
        $subscription->next_retry_date = $subscription->next_retry_date->addDays($microsite->retry_duration);
        Log::info('Payment rejected, retrying '.$subscription->next_retry_date);
        $subscription->save();
    }
}
