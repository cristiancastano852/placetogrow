<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Microsites;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index(Microsites $microsite)
    {
        $invoices = Invoice::where('microsite_id', $microsite->id)->latest()->paginate(10);

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'microsite' => $microsite,
        ]);
    }

    public function create() {}

    public function store(Request $request) {}

    public function show(Invoice $invoice) {}

    public function edit(Invoice $invoice) {}

    public function update(Request $request, Invoice $invoice) {}

    public function destroy(Invoice $invoice) {}
}
