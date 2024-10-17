<?php

use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('status', SubscriptionStatus::toArray())
                ->default(SubscriptionStatus::INACTIVE->value)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('status', [PaymentStatus::PENDING->value, PaymentStatus::APPROVED->value,
                PaymentStatus::PARTIAL_EXPIRED->value, PaymentStatus::REJECTED->value])
                ->default('PENDING')
                ->change();
        });
    }
};
