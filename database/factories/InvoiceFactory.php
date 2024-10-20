<?php

namespace Database\Factories;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\InvoiceStatus;
use App\Models\Microsites;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => $this->faker->word,
            'status' => $this->faker->randomElement(array_column(InvoiceStatus::cases(), 'name')),
            'document_number' => $this->faker->numerify('###########'),
            'document_type' => $this->faker->randomElement(array_column(DocumentTypes::cases(), 'name')),
            'name' => $this->faker->name,
            'surname' => $this->faker->name,
            'email' => $this->faker->email,
            'mobile' => $this->faker->phoneNumber,
            'description' => $this->faker->sentence,
            'currency' => $this->faker->randomElement(array_column(Currency::cases(), 'name')),
            'amount' => 10000,
            'expiration_date' => Carbon::now()->addMonth()->toDateTimeString(),
            'microsite_id' => Microsites::factory(),
        ];
    }
}
