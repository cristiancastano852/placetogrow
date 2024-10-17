<?php

namespace App\Console\Commands;

use App\Constants\SubscriptionStatus;
use App\Jobs\ProcessPaymentCollectSubscripcionJob;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Collect extends Command
{
    protected $signature = 'app:collect';

    protected $description = 'Collect subscription payments';

    public function handle()
    {
        Log::info('Collecting payment for subscription');

        $subscriptionsToCollect = Subscription::where('status', SubscriptionStatus::ACTIVE->value)
            ->where('next_billing_date', '<=', now())
            ->with('microsite:id,payment_expiration,payment_retries,retry_duration')
            ->get(['id', 'microsite_id', 'next_billing_date', 'expiration_date', 'billing_frequency', 'plan_id']);

        if ($subscriptionsToCollect->isEmpty()) {
            Log::info('No subscription to collect');

            return;
        }

        foreach ($subscriptionsToCollect as $subscription) {

            if ($subscription->expiration_date <= $subscription->next_billing_date) {
                Log::info('Subscription expired', ['subscription' => $subscription->id]);
                $subscription->status = SubscriptionStatus::EXPIRED->value;
                $subscription->save();

                continue;
            }

            ProcessPaymentCollectSubscripcionJob::dispatch($subscription);
        }
        Log::info('Finished processing subscriptions for payment collection');
    }
}
