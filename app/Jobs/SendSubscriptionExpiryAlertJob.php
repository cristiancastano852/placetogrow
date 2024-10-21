<?php

namespace App\Jobs;

use App\Constants\SubscriptionStatus;
use App\Mail\SubscriptionExpiryAlertMail;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionExpiryAlertJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct() {}

    public function handle(): void
    {
        Log::info('Sending subscription expiry alert mail');
        $today = Carbon::now();
        $daysBeforeExpiration = (int) config('subscriptions.expiry_alert_days', 7);
        $expiryDate = $today->copy()->addDays($daysBeforeExpiration)->format('Y-m-d');
        Log::info("Subscriptions with expiry date: {$expiryDate}");
        $subscriptions = Subscription::where('expiration_date', $expiryDate)
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->with([
                'microsite:id,late_fee_percentage',
                'user:id,name,email',
                'plan:id,name',
            ])
            ->get();

        if ($subscriptions->isEmpty()) {
            Log::info("No subscriptions found for expiry alert mail: {$expiryDate}");

            return;
        }

        foreach ($subscriptions as $subscription) {
            Log::info("Sending subscription expiry alert mail to: {$subscription->user->email}");
            Mail::to($subscription->user->email)->send(new SubscriptionExpiryAlertMail($subscription));
        }
    }
}
