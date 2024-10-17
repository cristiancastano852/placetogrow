<?php

namespace App\Jobs;

use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Payments\SubscriptionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPaymentCollectSubscripcionJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected Subscription $subscription;

    protected Microsites $microsite;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->microsite = $subscription->microsite;
    }

    public function tries(): int
    {
        return $this->microsite->payment_retries;
    }

    public function backoff(): int
    {
        return $this->microsite->retry_duration * 3600;

    }

    public function handle(): void
    {
        Log::info('Processing payment for subscription with ID '.$this->subscription->id.' | tries: '.$this->attempts());
        $microsite = $this->subscription->microsite;
        $subscriptionService = new SubscriptionService($microsite->payment_expiration, $this->subscription);
        $response = $subscriptionService->ProcessPaymentCollect();

        if ($response['status']['status'] === PaymentStatus::APPROVED->value) {
            $this->handleApprovedPayment();
        } else {
            $this->handleFailedPayment();
        }
    }

    private function handleApprovedPayment()
    {
        Log::info('Payment approved for subscription: '.$this->subscription->id);

        $billing_frequency = $this->subscription->billing_frequency;
        $plan = Plan::find($this->subscription->plan_id, ['duration_unit']);
        if (! $plan) {
            Log::error('Plan not found for subscription ID: '.$this->subscription->id);

            return;
        }

        $NewNextBillingDate = $this->subscription->next_billing_date->add($plan->duration_unit, $billing_frequency);
        $this->subscription->next_billing_date = $NewNextBillingDate;
        $this->subscription->save();
    }

    private function handleFailedPayment()
    {
        Log::warning('Payment rejected for subscription ID: '.$this->subscription->id.' | Attempt '.$this->attempts().' of '.$this->tries());
        if ($this->attempts() >= $this->tries()) {
            Log::info('Maximum attempts reached. Subscription suspended ID: '.$this->subscription->id);
            $this->subscription->status = SubscriptionStatus::SUSPENDED->value;
        } else {
            Log::info('Retrying payment for subscription ID: '.$this->subscription->id.' in '.$this->microsite->retry_duration.' hours.');
            $this->release($this->backoff());
        }
        $this->subscription->save();
    }
}
