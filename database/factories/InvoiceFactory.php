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
        $amount = 10000;
        $status = $this->faker->randomElement(array_column(InvoiceStatus::cases(), 'name'));
        $lateFeeAmount = 0;
        $totalAmount = $amount;
        if ($status === InvoiceStatus::EXPIRED->name) {
            $lateFeePercentage = $this->faker->randomFloat(2, 0, 0.2);
            $lateFeeAmount = $amount * $lateFeePercentage;
            $totalAmount = $amount + $lateFeeAmount;
        }

        return [
            'reference' => $this->faker->word.$this->faker->randomNumber(5),
            'status' => $status,
            'document_number' => $this->faker->numerify('###########'),
            'document_type' => $this->faker->randomElement(array_column(DocumentTypes::cases(), 'name')),
            'name' => $this->faker->name,
            'surname' => $this->faker->name,
            'email' => $this->faker->email,
            'mobile' => $this->faker->phoneNumber,
            'description' => $this->faker->sentence,
            'currency' => $this->faker->randomElement(array_column(Currency::cases(), 'name')),
            'amount' => $amount,
            'late_fee_amount' => $lateFeeAmount,
            'total_amount' => $totalAmount,
            'expiration_date' => Carbon::now()->addMonth()->toDateTimeString(),
            'microsite_id' => Microsites::factory(),
        ];
    }
}
