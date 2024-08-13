<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 0,
            'set_id' => 0,
            'result' => fake()->numberBetween(1, 100),
            'num_questions' => fake()->numberBetween(1, 25),
        ];
    }

    public function user($userId)
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'user_id' => $userId,
            ];
        });
    }

    public function exam($examId)
    {
        return $this->state(function (array $attributes) use ($examId) {
            return [
                'set_id' => $examId,
            ];
        });
    }
}
