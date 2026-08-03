<?php

namespace Database\Factories;

use App\Models\Major;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Major>
 */
class MajorFactory extends Factory
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
                'en' =>fake()->unique()->word(),
                'ar' => fake()->unique()->word(),
            ],
            'degree' => fake()->randomElement(['bachelor' , 'diploma', 'master', 'phd']),
            'is_active' => fake()->boolean(),
            'department_id' => \App\Models\Department::inRandomOrder()->first()?->id ?? \App\Models\Department::factory(),
        ];
    }
}
