<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetAmountMounthlyByMicrosite');
        $path = database_path('sql/get_amount_total_monthly_by_microsite.sql');
        $sql = File::get($path);
        DB::unprepared($sql);
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetAmountMounthlyByMicrosite');
    }
};
