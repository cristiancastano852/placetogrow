<?php

use App\Constants\TimeUnitSubscription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('microsite_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('price');
            $table->text('description')->nullable();
            $table->enum('duration_unit', TimeUnitSubscription::toArray())->default(TimeUnitSubscription::MONTH);
            $table->integer('billing_frequency');
            $table->integer('duration_period');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
