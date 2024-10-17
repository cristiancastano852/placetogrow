<?php

namespace App\Actions\Subscription;

use App\Constants\SubscriptionStatus;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class StoreSubscriptionAction
{
    public function execute(User $user, Microsites $microsite, Plan $plan): Subscription
    {
        $reference = 'SUBS-'.now()->format('YmdHis').'-'.$user->id;
        $description = $plan->description;
        $price = $plan->price;
        $user_id = $user->id;
        $microsite_id = $microsite->id;
        $status = SubscriptionStatus::INACTIVE->value;
        $billing_frequency = $plan->billing_frequency;
        $expiration_date = now()->addMonths($plan->duration_period);
        $duration_unit = $plan->duration_unit;
        $next_billing_date = Carbon::now()->add($billing_frequency, $duration_unit);
        $next_retry_date = $next_billing_date;
        $name = $plan->name;
        $subscription = new Subscription();

        $subscription->fill([
            'plan_id' => $plan->id,
            'user_id' => $user_id,
            'microsite_id' => $microsite_id,
            'reference' => $reference,
            'description' => $description,
            'status' => $status,
            'name' => $name,
            'price' => $price,
            'billing_frequency' => $billing_frequency,
            'expiration_date' => $expiration_date,
            'next_billing_date' => $next_billing_date,
            'next_retry_date' => $next_retry_date,
        ]);
        $subscription->save();

        return $subscription;
    }
}
