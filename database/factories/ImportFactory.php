<?php

namespace Database\Factories;

use App\Constants\ImportStatus;
use App\Models\Microsites;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Import>
 */
class ImportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => 'file.csv',
            'file_name' => 'file.csv',
            'status' => fake()->randomElement(ImportStatus::toArray()),
            'errors' => [],
            'user_id' => User::factory(),
            'microsite_id' => Microsites::factory(),
        ];
    }
}
