<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->text('token')->nullable()->change();
            $table->text('subtoken')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('token', 70)->nullable(false)->change();
            $table->string('subtoken', 50)->nullable(false)->change();
        });
    }
};
