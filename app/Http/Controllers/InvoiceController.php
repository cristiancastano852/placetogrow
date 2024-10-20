<?php

namespace App\Http\Controllers;

use App\Constants\PolicyName;
use App\Factories\PaymentDataProviderFactory;
use App\Models\Invoice;
use App\Models\Microsites;
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

        $this->authorize(PolicyName::VIEW_ANY, Invoice::class);
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

    public function invoicesByUser(Request $request): Response
    {
        $status = $request->input('status') ?? null;
        $user = Auth::user();
        $invoices = Invoice::invoicesByRole($user, $status)->latest()->paginate(30);

        return Inertia::render('Invoices/InvoicesUser', [
            'invoices' => $invoices,
        ]);
    }

    public function invoicesPayment(Request $request, Microsites $microsite, PaymentDataProviderFactory $factory)
    {
        $invoiceId = $request->invoice_id;
        $invoice = Invoice::where('microsite_id', $microsite->id)
            ->where('id', $invoiceId)
            ->first();
        if ($invoice->expiration_date < now() && $invoice->late_fee_amount <= 0) {
            $invoice->applyLateFee();
        }
        $user = Auth::user();
        $paymentRepository = new PaymentRepository();
        $invoiceData = [
            'description' => $invoice->description,
            'amount' => $invoice->total_amount,
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
        $invoice->updateStatusToInProcess();

        return Inertia::location($response->url);
    }
}
