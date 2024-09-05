<?php

namespace App\Http\Controllers;

use App\Constants\PaymentStatus;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request, Microsites $microsite)
    {
        $plan = Plan::where('id', $request->plan_id)->first();
        $user = auth()->user();

        $reference = 'SUBS-'.now()->format('YmdHis').'-'.$user->id;
        $description = $plan->description;
        $price = $plan->price;
        $user_id = $user->id;
        $microsite_id = $microsite->id;
        $status = PaymentStatus::PENDING;
        $billing_frequency = $plan->billing_frequency;
        $expiration_date = now()->addMonths($plan->duration_period);
        $name = $plan->name;

        $subscription = Subscription::query()->create([
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
        ]);

        $login = env('PLACETOPAY_LOGIN');
        $secretKey = env('PLACETOPAY_SECRET_KEY');
        $seed = Carbon::now()->toIso8601String();
        $nonce = Str::random();

        $tranKey = base64_encode(hash('sha256', $nonce.$seed.$secretKey, true));
        $nonce = base64_encode($nonce);

        $data = [
            'auth' => [
                'login' => $login,
                'tranKey' => $tranKey,
                'nonce' => $nonce,
                'seed' => $seed,
            ],
            'buyer' => [
                'name' => $user->name,
                'email' => $user->email,
                'documentType' => $user->document_type,
                'document' => $user->document_number,
            ],
            'reference' => $reference,
            'description' => $description,
            'expiration' => now()->addMinutes(19)->toIso8601String(),
            'ipAddress' => request()->ip(),
            'userAgent' => request()->userAgent(),
            'returnUrl' => route('subscriptions.show', [
                'subscription' => $subscription->id,
                'microsite' => $microsite->id,
            ]),
            'subscription' => [
                'reference' => $reference,
                'description' => $description,
            ],
        ];

        $response = Http::post(env('PLACETOPAY_URL'), $data);

        if ($response['status']['status'] !== 'OK') {

            Log::error('Error creating session', [
                'response' => $response,
            ]);

        }

        $subscription->update([
            // 'process_url' => $response['processUrl'],
            'request_id' => $response['requestId'],
            'status_message' => $response['status']['message'],
        ]);

    }

    public function return(Microsites $microsite, Subscription $subscription)
    {
        $login = env('PLACETOPAY_LOGIN');
        $secretKey = env('PLACETOPAY_SECRET_KEY');
        $seed = Carbon::now()->toIso8601String();
        $nonce = Str::random();

        $tranKey = base64_encode(hash('sha256', $nonce.$seed.$secretKey, true));
        $nonce = base64_encode($nonce);

        $auth = [
            'login' => $login,
            'tranKey' => $tranKey,
            'nonce' => $nonce,
            'seed' => $seed,
        ];
        $URL = env('PLACETOPAY_URL').'/'.$subscription->request_id;
        $sssionInformationResponse = Http::post($URL, [
            'auth' => $auth,
        ]);

        $subscription->update([
            'status' => $sssionInformationResponse['status']['status'],
            'token' => $sssionInformationResponse['subscription']['instrument'][0]['value'],
            'subtoken' => $sssionInformationResponse['subscription']['instrument'][1]['value'],
        ]);

        return redirect()->route('microsites.show', $microsite);
    }
}
