<?php

namespace App\Console\Commands;

use App\Constants\SubscriptionStatus;
use App\Jobs\ProcessPendingSubscriptionJob;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSubscriptionsPending extends Command
{

    protected $signature = 'app:check-subscriptions-pending';

    protected $description = 'Check subscriptions pending';

    public function handle()
    {
        Log::info('Checking subscriptions pending');

        $subscriptionsPending = Subscription::where('status', SubscriptionStatus::PENDING->value)
            ->get(['id', 'microsite_id', 'next_billing_date', 'expiration_date', 'billing_frequency', 'plan_id']);

        if ($subscriptionsPending->isEmpty()) {
            Log::info('No subscription pending');
            return;
        }

        foreach ($subscriptionsPending as $subscription) {
            Log::info('Processing subscription pending', ['subscription' => $subscription->id]);
            ProcessPendingSubscriptionJob::dispatch($subscription);
        }
    }
}
