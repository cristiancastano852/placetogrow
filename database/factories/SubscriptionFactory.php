<?php

namespace Database\Factories;

use App\Constants\SubscriptionStatus;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $payer = [
            'name' => $this->faker->name,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->email,
            'mobile' => $this->faker->phoneNumber,
            'documentType' => $this->faker->randomElement(['CC']),
            'document' => $this->faker->numerify('###########'),
        ];

        $plan = Plan::factory()->create();

        return [
            'user_id' => User::factory(),
            'microsite_id' => Microsites::factory(),
            'plan_id' => $plan->id,
            'reference' => $this->faker->unique()->word,
            'description' => $this->faker->word,
            'status' => SubscriptionStatus::ACTIVE->value,
            'status_message' => $this->faker->sentence,
            'request_id' => $this->faker->word,
            'name' => $this->faker->word,
            'token' => $this->faker->optional()->word,
            'subtoken' => $this->faker->optional()->word,
            'price' => $this->faker->numberBetween(1000, 10000),
            'expiration_date' => Carbon::now()->addMonths(12),
            'billing_frequency' => $plan->billing_frequency,
            'next_billing_date' => Carbon::now()->addMonth($plan->billing_frequency),
            'payer' => $payer,
            'next_retry_date' => Carbon::now()->addMonth($plan->billing_frequency),
            'retry_attempts' => 0,
        ];
    }
}
