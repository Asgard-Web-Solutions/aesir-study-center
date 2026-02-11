<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Set>
 */
class SetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->words(25, true),
            'user_id' => 0,
            'visibility' => 1,
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

    public function visibility($visible)
    {
        return $this->state(function (array $attributes) use ($visible) {
            return [
                'visibility' => $visible,
            ];
        });
    }
}
