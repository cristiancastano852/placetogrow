<?php

namespace App\Http\Controllers;

use App\Constants\ImportStatus;
use App\Constants\MicrositesTypes;
use App\Imports\InvoiceImport;
use App\Models\Import;
use App\Models\Microsites;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Excel as Reader;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function create(Microsites $microsite): \Inertia\Response
    {
        if ($microsite->site_type !== MicrositesTypes::Facturas->name) {
            abort(404);
        }

        return Inertia::render('Imports/Create', [
            'microsite' => $microsite,
        ]);
    }

    public function store(Request $request, Microsites $microsite): \Inertia\Response
    {
        $file = $request->file('file');

        $path = $file->store(options: ['disk' => Import::DISK]);

        $import = new Import();
        $import->path = $path;
        $import->file_name = $file->getClientOriginalName();
        $import->status = ImportStatus::PENDING;
        $import->microsite()->associate($microsite);
        $import->user()->associate(auth()->user());
        $import->save();
        Excel::import(new InvoiceImport($import), $import->path, Import::DISK, Reader::CSV);

        return Inertia::render('imports.show', [
            'import' => $import,
        ]);
    }

    public function show(Import $import): View
    {
        $this->authorize('view', $import);

        return view('imports.show', [
            'import' => $import,
        ]);
    }
}
