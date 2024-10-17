<?php

namespace Tests\Feature\Import\Jobs;

use App\Constants\ImportStatus;
use App\Constants\MicrositesTypes;
use App\Imports\InvoiceImport;
use App\Models\Import;
use App\Models\Microsites;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ImportInvoicesJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_invoices_job_excel_success(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $this->actingAs($user);
        $microsite = Microsites::factory()->create([
            'site_type' => MicrositesTypes::Facturas->name,
        ]);

        $import = Import::factory()->create([
            'microsite_id' => $microsite->id,
            'status' => ImportStatus::PENDING,
            'user_id' => $user->id,
            'file_name' => 'valid_invoices.csv',
            'errors' => [],
            'path' => 'imports/valid_invoices.csv',
        ]);

        $file_path = 'imports/valid_invoices.csv';
        Storage::disk('local')->put($file_path, file_get_contents(base_path('tests/data/valid_invoices.csv')));
        Excel::import(new InvoiceImport($import), $file_path, 'local');

        $this->assertDatabaseHas('invoices', [
            'microsite_id' => $microsite->id,
            'status' => ImportStatus::PENDING,
        ]);

    }

    public function test_import_invoices_job_excel_failed(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $this->actingAs($user);
        $microsite = Microsites::factory()->create([
            'site_type' => MicrositesTypes::Facturas->name,
        ]);

        $import = Import::factory()->create([
            'microsite_id' => $microsite->id,
            'status' => ImportStatus::PENDING,
            'user_id' => $user->id,
            'file_name' => 'invalid_invoices.csv',
            'errors' => [],
            'path' => 'imports/invalid_invoices.csv',
        ]);
        $file_path = 'imports/invalid_invoices.csv';
        Storage::disk('local')->put($file_path, file_get_contents(base_path('tests/data/invalid_invoices.csv')));
        try {
            Excel::import(new InvoiceImport($import), $file_path, 'local');
        } catch (\Throwable  $e) {
            $import->refresh();
            $this->assertDatabaseHas('imports', [
                'id' => $import->id,
                'status' => ImportStatus::FAILED,
            ]);
        }
        $import->refresh();
        $this->assertDatabaseHas('imports', [
            'id' => $import->id,
            'status' => ImportStatus::FAILED,
        ]);

    }
}
