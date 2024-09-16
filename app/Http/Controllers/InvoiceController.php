<?php

namespace App\Http\Controllers;

use App\Factories\PaymentDataProviderFactory;
use App\Models\Invoice;
use App\Models\Microsites;
use App\Models\User;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(Microsites $microsite)
    {
        return Inertia::render('Invoices/Index', [
            'microsite' => $microsite,
        ]);
    }

    public function invoicesByMicrosite(Microsites $microsite)
    {
        $invoices = Invoice::where('microsite_id', $microsite->id)->latest()->paginate(10);

        return Inertia::render('Invoices/Show', [
            'invoices' => $invoices,
            'microsite' => $microsite,
        ]);
    }

    public function invoicesByDocument(Request $request, Microsites $microsite)
    {
        $document_number = $request->document_number;
        $invoices = Invoice::where('microsite_id', $microsite->id)
            ->where('document_number', $document_number)
            ->latest()
            ->paginate(10);

        return Inertia::render('Invoices/ShowByUser', [
            'invoices' => $invoices,
            'microsite' => $microsite,
            'document_number' => $document_number,
        ]);
    }

    public function invoicesByUser(): Response
    {
        $user = User::find(Auth::user()->id);
        $invoices = Invoice::invoicesByRole($user)->latest()->paginate(10);

        return Inertia::render('Invoices/InvoicesUser', [
            'invoices' => $invoices,
        ]);
    }

    public function invoicesPayment(Request $request, Microsites $microsite, PaymentDataProviderFactory $factory)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::where('microsite_id', $microsite->id)
            ->where('id', $invoice_id)
            ->first();
        $user = User::find(Auth::user()->id);
        $paymentRepository = new PaymentRepository();
        $invoiceData = [
            'description' => $invoice->description,
            'amount' => $invoice->amount,
            'reference' => $invoice->reference,
            'invoice_id' => $invoice->id,

        ];
        $payment = $paymentRepository->create($invoiceData, $user, $microsite);

        $buyerData = [
            'name' => $invoice->name,
            'last_name' => $invoice->surname,
            'email' => $invoice->email,
            'document_number' => $invoice->document_number,
            'document_type' => $invoice->document_type,
        ];

        $paymentService = $factory->make($payment, $microsite);
        $response = $paymentService->create($buyerData);
        if ($response->status === 'exception') {
            Log::error('Payment creation exception', [
                'buyer' => $buyerData,
                'payment' => $payment,
                'message' => $response->message,
            ]);

            return back()->withErrors(['message' => $response->message]);
        }

        return Inertia::location($response->url);
    }
}
