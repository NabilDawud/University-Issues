<?php

namespace Database\Factories;

use App\Models\Deanship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Deanship>
 */
class DeanshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'en' => fake()->unique()->company(),
                'ar' => fake()->unique()->company(),
            ],
            'code' => fake()->unique()->countryCode(),
            'email' => fake()->unique()->companyEmail(),
            'extension_number' => fake()->phoneNumber(),
            'office_number' => fake()->buildingNumber(),
            'is_active' => fake()->boolean(),
            'description' => fake()->paragraph(),
        ];
    }
}
