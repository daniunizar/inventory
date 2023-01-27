<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Boardgame>
 */
class BoardgameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'label' => fake()->word(),
            'description' => fake()->sentence(),
            'editorial' => fake()->word(),
            'min_players' => fake()->numberBetween($min=1, $max=2),
            'max_players'=>fake()->numberBetween($min=4, $max=10),
            'user_id'=>fake()->numberBetween($min=1, $max=10),
        ];
    }
}
