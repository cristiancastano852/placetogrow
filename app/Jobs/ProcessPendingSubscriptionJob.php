<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Services\Payments\SubscriptionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPendingSubscriptionJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue;

    protected Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle(): void
    {
        $microsite = $this->subscription->microsite;
        $subscriptionService = new SubscriptionService($microsite->payment_expiration, $this->subscription);
        Log::info('Check pending subscription', ['subscription' => $this->subscription]);
        $response = $subscriptionService->checkSubscriptionStatus();
    }
}
