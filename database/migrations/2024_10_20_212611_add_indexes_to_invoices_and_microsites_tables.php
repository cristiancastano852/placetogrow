<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->index('expiration_date', 'idx_invoices_expiration_date');

            $table->index('email', 'idx_invoices_email');

            $table->index(['expiration_date', 'status', 'email'], 'idx_invoices_date_status_email');

            $table->index('microsite_id', 'idx_invoices_microsite_id');
        });

        Schema::table('microsites', function (Blueprint $table) {
            $table->index(['user_id', 'id'], 'idx_microsites_user_microsite');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_expiration_date');
            $table->dropIndex('idx_invoices_email');
            $table->dropIndex('idx_invoices_date_status_email');
            $table->dropIndex('idx_invoices_microsite_id');
        });

        Schema::table('microsites', function (Blueprint $table) {
            $table->dropIndex('idx_microsites_user_microsite');
        });
    }
};
