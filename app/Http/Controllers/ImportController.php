<?php

namespace App\Http\Controllers;

use App\Constants\ImportStatus;
use App\Constants\MicrositesTypes;
use App\Constants\PolicyName;
use App\Imports\InvoiceImport;
use App\Models\Import;
use App\Models\Microsites;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Excel as Reader;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function create(Microsites $microsite): \Inertia\Response
    {
        $this->authorize(PolicyName::VIEW, Import::class);
        if ($microsite->site_type !== MicrositesTypes::Facturas->name) {
            abort(404);
        }
        $imports = Import::where('microsite_id', $microsite->id)->latest()->paginate(10);

        return Inertia::render('Imports/Create', [
            'microsite' => $microsite,
            'imports' => $imports,
        ]);
    }

    public function store(Request $request, Microsites $microsite)
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

        return Inertia::location(route('import.create', $microsite));
    }
}
