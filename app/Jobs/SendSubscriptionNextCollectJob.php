<?php

namespace App\Jobs;

use App\Constants\SubscriptionStatus;
use App\Mail\SubscriptionNextCollectAlertMail;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionNextCollectJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct() {}

    public function handle(): void
    {
        Log::info('Sending subscription expiry alert mail');
        $today = Carbon::now();
        $next_billing_date = $today->copy()->addDays(2)->format('Y-m-d');
        Log::info("Subscriptions with next billing date: {$next_billing_date}");
        $subscriptions = Subscription::where('next_billing_date', $next_billing_date)
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->with(['user', 'microsite', 'plan']) //definir parametros
            ->get();

        if ($subscriptions->isEmpty()) {
            Log::info("No subscriptions found for expiry alert mail: {$next_billing_date}");

            return;
        }

        foreach ($subscriptions as $subscription) {
            Log::info("Sending subscription collect alert mail to: {$subscription->user->email}");
            Mail::to($subscription->user->email)->send(new SubscriptionNextCollectAlertMail($subscription));
        }
    }
}
