<?php

namespace App\Http\Controllers;

use App\Actions\Subscription\StoreSubscriptionAction;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Payments\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function store(StoreSubscriptionRequest $request, StoreSubscriptionAction $storeAction, Microsites $microsite)
    {

        $data = $request->validated();
        $user = User::find(Auth::user()->id);
        $plan = Plan::findOrFail($request->plan_id);
        $subscription = $storeAction->execute($user, $microsite, $plan);
        $subscriptionService = new SubscriptionService($microsite->payment_expiration, $subscription);
        $response = $subscriptionService->create($user);
        if ($response['status']['status'] !== 'OK') {

            Log::error('Error creating session', [
                'response' => $response,
            ]);

        }

        $subscription->update([
            'request_id' => $response['requestId'],
            'status_message' => $response['status']['message'],
        ]);

        return Inertia::location($response['processUrl']);

    }

    public function return(Microsites $microsite, Subscription $subscription)
    {
        $subscriptionService = new SubscriptionService($microsite->payment_expiration, $subscription);
        $subscription = $subscriptionService->query();

        return Inertia::render('Subscription/Show', [
            'subscription' => $subscription,
            'microsite' => $microsite,
        ]);
    }

    public function show(Microsites $microsite, Subscription $subscription)
    {
        return Inertia::render('Subscriptions/Show', [
            'subscription' => $subscription,
            'microsite' => $microsite,
        ]);
    }
}
