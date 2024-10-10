<?php

namespace App\Http\Controllers;

use App\Models\Microsites;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MicrositePaymentController extends Controller
{
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
