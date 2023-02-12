<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Test;

class TestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Test::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'num_questions' => $this->faker->randomNumber(),
            'result' => $this->faker->randomFloat(),
            'set_id' => \App\Models\Set::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
