<?php

use Illuminate\Support\Facades\File;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetInvoicesDueExpireAlert');
        $path = database_path('sql/get_invoices_by_status_and_date.sql');
        $sql = File::get($path);
        DB::unprepared($sql);

    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetInvoicesDueExpireAlert');
    }
};
