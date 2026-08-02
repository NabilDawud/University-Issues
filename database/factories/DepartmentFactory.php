<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
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
            'code' => fake()->unique()->numberBetween(1000, 9999),
            'email' => fake()->unique()->companyEmail(),
            'extension_number' => fake()->phoneNumber(),
            'office_number' => fake()->buildingNumber(),
            'is_active' => fake()->boolean(),
            'description' => fake()->paragraph(),
            'deanship_id' =>  \App\Models\Deanship::inRandomOrder()->first()?->id ?? \App\Models\Deanship::factory(),
        ];
    }
}
