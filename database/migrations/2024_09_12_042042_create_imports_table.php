<?php

use App\Constants\ImportStatus;
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
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('file_name');
            $table->enum('status', ImportStatus::toArray());
            $table->json('errors')->nullable();

            $table->foreignId('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreignId(('microsite_id'));
            $table->foreign('microsite_id')
                ->references('id')
                ->on('microsites');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
