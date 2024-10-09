<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('microsites', function (Blueprint $table) {
            $table->integer('payment_retries')->nullable();
            $table->integer('retry_duration')->nullable();
            $table->decimal('late_fee_percentage', 5, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('microsites', function (Blueprint $table) {
            $table->dropColumn('payment_retries');
            $table->dropColumn('retry_duration');
            $table->dropColumn('late_fee_percentage');
        });
    }
};
