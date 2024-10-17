<?php

use App\Constants\SubscriptionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('microsite_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique();
            $table->text('description')->nullable();
            $table->enum('status', SubscriptionStatus::toArray())->default(SubscriptionStatus::PENDING->value);
            $table->string('status_message')->nullable();
            $table->string('request_id')->nullable();
            $table->string('name');
            $table->string('token', 70)->nullable();
            $table->string('subtoken', 50)->nullable();
            $table->integer('price');
            $table->date('expiration_date');
            $table->integer('billing_frequency');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
