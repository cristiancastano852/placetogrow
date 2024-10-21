<?php

namespace Database\Factories;

use App\Constants\SubscriptionStatus;
use App\Models\Microsites;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

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

        return [
            'user_id' => User::factory(),
            'microsite_id' => Microsites::factory(),
            'plan_id' => Plan::factory(),
            'reference' => $this->faker->unique()->word.$this->faker->unique()->word,
            'description' => $this->faker->word,
            'status' => SubscriptionStatus::ACTIVE->value,
            'status_message' => $this->faker->sentence,
            'request_id' => $this->faker->word,
            'name' => $this->faker->word,
            'token' => Crypt::encryptString($this->faker->word),
            'subtoken' => Crypt::encryptString($this->faker->word),
            'price' => $this->faker->numberBetween(1000, 10000),
            'expiration_date' => Carbon::now()->addMonths(12),
            'billing_frequency' => 1,
            'next_billing_date' => Carbon::now()->addMonths(1),
            'payer' => $payer,
        ];
    }
}
