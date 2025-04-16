<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cohort>
 */
class CohortFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'name' => 'Cohorte ' . fake()->unique()->word(),
            'description' => fake()->sentence(),
            'start_date' => now()->subMonths(1)->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
        ];
    }
}

