<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('late_fee_amount')->nullable()->after('amount');
            $table->unsignedBigInteger('total_amount')->nullable()->after('late_fee_amount');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('late_fee_amount');
            $table->dropColumn('total_amount');
        });
    }
};
