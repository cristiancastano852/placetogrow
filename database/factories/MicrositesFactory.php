<?php

namespace Database\Factories;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Microsites>
 */
class MicrositesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyName = $this->faker->company().' '.$this->faker->randomFloat(2, 0, 100);
        $slug = Str::slug($companyName, '-');
        $paymentFields = [
            ['name' => 'description', 'type' => 'input', 'label' => 'Dame un notica :)', 'optional' => true, 'validation' => null, 'placeholder' => 'Los amo <3'],
            ['name' => 'name', 'type' => 'input', 'label' => 'Nombre', 'optional' => false, 'validation' => null, 'placeholder' => 'Juan Perez'],
            ['name' => 'email', 'type' => 'input', 'label' => 'Correo electrónico', 'optional' => false, 'validation' => null, 'placeholder' => 'juan@mail.com'],
            ['name' => 'document_type', 'type' => 'select', 'label' => 'Tipo de documento', 'optional' => false, 'validation' => null, 'placeholder' => 'juan@mail.com'],
            ['name' => 'document_number', 'type' => 'input', 'label' => 'Número de documento', 'optional' => false, 'validation' => null, 'placeholder' => '123456789'],
        ];

        return [
            'slug' => substr($slug, 0, 20),
            'name' => substr($this->faker->company(), 0, 30),
            'document_type' => $this->faker->randomElement(array_column(DocumentTypes::cases(), 'name')),
            'document_number' => $this->faker->numerify('###########'),
            'logo' => $this->faker->imageUrl(),
            'category_id' => Category::all()->random()->id,
            'currency' => $this->faker->randomElement(array_column(Currency::cases(), 'name')),
            'payment_expiration' => $this->faker->numberBetween(20, 30),
            'user_id' => User::factory(),
            'site_type' => $this->faker->randomElement(array_column(MicrositesTypes::cases(), 'name')),
            'payment_fields' => $paymentFields,
            'payment_retries' => $this->faker->numberBetween(1, 3),
            'retry_duration' => $this->faker->numberBetween(1, 3),
            'late_fee_percentage' => $this->faker->randomFloat(2, 0, 100),

        ];
    }
}
