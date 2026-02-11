<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'set_id' => 0,
            'text' => fake()->words(6, true),
            'group_id' => 0,
        ];
    }

    public function exam($setId)
    {
        return $this->state(function (array $attributes) use ($setId) {
            return [
                'set_id' => $setId,
            ];
        });
    }

    public function group($groupId)
    {
        return $this->state(function (array $attributes) use ($groupId) {
            return [
                'group_id' => $groupId,
            ];
        });
    }
}
