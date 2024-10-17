<?php

namespace Database\Factories;

use App\Constants\TimeUnitSubscription;
use App\Models\Microsites;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'microsite_id' => Microsites::factory(),
            'name' => $this->faker->name(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'description' => $this->faker->sentence,
            'duration_unit' => TimeUnitSubscription::MONTH,
            'billing_frequency' => $this->faker->randomElement([1, 3, 6]),
            'duration_period' => $this->faker->randomElement([6, 12]),
        ];
    }
}
