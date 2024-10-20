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
        $payments = Payment::where('microsite_id', $microsite_id)
            ->with('microsite:id,name')
            ->select('id', 'reference', 'description', 'status', 'amount', 'currency', 'microsite_id', 'updated_at', 'subscription_id', 'invoice_id')
            ->orderBy('created_at', 'desc')
            ->get();

        $microsites = Microsites::MicrositesByUser(Auth::user())->get();

        return Inertia::render('Payments/Transactions', [
            'payments' => $payments,
            'microsites' => $microsites,
        ]);
    }
}
