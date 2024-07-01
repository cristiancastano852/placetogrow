<?php

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('microsites', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name', 100);
            $table->foreignId('category_id')->constrained();
            $table->enum('document_type', array_column(DocumentTypes::cases(), 'name'));
            $table->string('document_number', 20);
            $table->string('logo');
            $table->enum('currency', array_column(Currency::cases(), 'name'));
            $table->enum('site_type', array_column(MicrositesTypes::cases(), 'name'));
            $table->integer('payment_expiration')->default(10); // Minutes
            $table ->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('microsites');
    }
};
