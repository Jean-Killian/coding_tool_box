<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'subject' => fake()->randomElement(['PHP', 'Laravel', 'JavaScript']),
            'question_count' => 5,
            'questions' => [
                [
                    'question' => 'Quelle est la bonne réponse ?',
                    'options' => ['A', 'B', 'C', 'D'],
                    'answer' => 'A',
                    'difficulty' => 'moyen',
                ]
            ],
            'is_published' => true,
        ];
    }
}

