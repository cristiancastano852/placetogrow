<?php

namespace App\Http\Controllers;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Microsites;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request)
    {
        $user = User::find(Auth::user()->id);
        $gateway = $request->gateway;
        $microsite_id = $request->microsite_id;
        $microsite = Microsites::find($microsite_id);
        $paymentRepository = new PaymentRepository();
        $payment = $paymentRepository->create($request->all(), $user, $microsite, $gateway);
        $buyerData = $paymentRepository->buyer($request)->getBuyerData();

        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $gateway,
        ]);

        $response = $paymentService->create($buyerData);

        return Inertia::location($response->url);
    }

    public function show(Payment $payment): \Inertia\Response
    {
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $payment->gateway,
        ]);

        if ($payment->status === PaymentStatus::PENDING->value) {
            $payment = $paymentService->query();
        }

        return Inertia::render('Payments/Show', [
            'payment' => $payment,
        ]);
    }

    public function transactions(): \Inertia\Response
    {
        $user = Auth::user();
        $payments = [];
        $userRole = $user->roles->first()->name;
        $microsites = Microsites::all();

        if ($userRole === 'Admin') {
            $payments = Payment::with('microsite')->get();
        }
        if ($userRole === 'Customer') {
            $microsites = Microsites::where('user_id', $user->id)->get();
            $payments = Payment::where('user_id', $user->id)
                ->orWhereIn('microsite_id', $microsites->pluck('id'))
                ->with('microsite')
                ->get();
        }

        if ($userRole === 'Guests') {
            $payments = Payment::where('user_id', $user->id)
                ->with('microsite')
                ->get();
        }

        return Inertia::render('Payments/Transactions', [
            'payments' => $payments,
            'microsites' => $microsites,
        ]);
    }

    public function transactionsByMicrosite($microsite_id): \Inertia\Response
    {
        $user = Auth::user();
        $payments = Payment::where('microsite_id', $microsite_id)
            ->with('microsite')
            ->get();

        $microsites = Microsites::all();

        return Inertia::render('Payments/Transactions', [
            'payments' => $payments,
            'microsites' => $microsites,
        ]);
    }
}
