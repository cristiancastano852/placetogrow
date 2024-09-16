<?php

namespace App\Http\Controllers;

use App\Actions\Subscription\StoreSubscriptionAction;
use App\Constants\SubscriptionStatus;
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
    public function index(): \Inertia\Response
    {

        $user = Auth::user();
        $subscriptions = [];
        $microsites = Microsites::where('site_type', 'Subscripciones')->get();
        $subscriptions = Subscription::SubscriptionsByRole($user)->get();

        return Inertia::render('Subscription/Index', [
            'subscriptions' => $subscriptions,
            'microsites' => $microsites,
        ]);
    }

    public function store(StoreSubscriptionRequest $request, StoreSubscriptionAction $storeAction, Microsites $microsite)
    {

        $data = $request->validated();
        $user = User::find(Auth::user()->id);
        $plan = Plan::findOrFail($request->plan_id);
        $subscription = $storeAction->execute($user, $microsite, $plan);
        $subscriptionService = new SubscriptionService($microsite->payment_expiration, $subscription);
        $response = $subscriptionService->create($user);
        if ($response['status']['status'] !== 'OK') {
            Log::error('Error creating session', ['response' => $response]);
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

    public function cancel(Subscription $subscription)
    {
        $microsite = Microsites::find($subscription->microsite_id);
        $subscriptionService = new SubscriptionService($microsite->payment_expiration, $subscription);
        $response = $subscriptionService->cancel();

        if ($response == null || $response['status']['status'] !== 'OK') {
            Log::error('Error canceling subscription', [
                'response' => $response,
            ]);
        }

        $subscription->update([
            'status' => SubscriptionStatus::CANCELED->value,
            'status_message' => $response['status']['message'] ?? null,
        ]);

        return redirect()->route('subscriptions.index');
    }
}
