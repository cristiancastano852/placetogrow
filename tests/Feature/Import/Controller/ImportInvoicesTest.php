<?php

namespace Tests\Feature\Import\Controller;

use App\Constants\MicrositesTypes;
use App\Models\Microsites;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ImportInvoicesTest extends TestCase
{
    public function test_create_import_invoices_success(): void
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $microsite = Microsites::factory()->create([
            'site_type' => MicrositesTypes::Facturas->name,
        ]);

        $response = $this->get(route('import.create', $microsite));

        $response->assertStatus(200);

    }

    public function test_store_import_invoices_success(): void
    {

        Storage::fake('local');
        Excel::fake();
        Queue::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $microsite = Microsites::factory()->create([
            'site_type' => MicrositesTypes::Facturas->name,
        ]);

        $file = new UploadedFile(base_path('tests/data/valid_invoices.csv'), 'valid_invoices.csv', 'text/csv', null, true);
        $response = $this->post(route('import.store', $microsite), [
            'file' => $file,
        ]);
        $response->assertStatus(302);

    }
}
