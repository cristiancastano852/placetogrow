<?php

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\InvoiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 80);
            $table->enum('status', array_column(InvoiceStatus::cases(), 'name'));
            $table->string('document_number', 20);
            $table->enum('document_type', array_column(DocumentTypes::cases(), 'name'));
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('email', 100);
            $table->string('mobile', 20);
            $table->string('description', 100);
            $table->enum('currency', array_column(Currency::cases(), 'name'));
            $table->unsignedBigInteger('amount');
            $table->date('expiration_date');
            $table->foreignId(('microsite_id'));
            $table->foreign('microsite_id')
                ->references('id')
                ->on('microsites');
            $table->unique(['microsite_id', 'reference']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
