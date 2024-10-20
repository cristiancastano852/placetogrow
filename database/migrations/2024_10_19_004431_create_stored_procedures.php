<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetInvoiceMetrics');
        $path = database_path('sql/count_invoices_by_status.sql');
        $sql = File::get($path);
        DB::unprepared($sql);

    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetInvoiceMetrics');
    }
};
