<?php

namespace Database\Factories;

use App\Constants\Currency;
use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Models\Microsites;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(),
            'reference' => Str::random(),
            'amount' => 10000,
            'microsite_id' => Microsites::factory(),
            'currency' => Currency::USD->name,
            'gateway' => PaymentGateway::PLACETOPAY->value,
            'status' => PaymentStatus::PENDING->value,
            'user_id' => User::factory(),
        ];
    }
}
