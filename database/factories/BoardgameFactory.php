<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Boardgame;
use Illuminate\Support\Facades\Log;

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
            'min_age' => fake()->numberBetween($min=0, $max=10),
            'max_age'=>fake()->numberBetween($min=11, $max=99),
            'user_id'=>fake()->numberBetween($min=1, $max=10),
        ];
    }

    public function withTags(array $tag_ids)
    {
        Log::debug("Entrando en with tags:");

        return $this->afterCreating(function (Boardgame $boardgame) use ($tag_ids) {
            foreach($tag_ids as $tag_id){
                Log::debug($tag_id);
                $boardgame->tags()->attach($tag_id);
            }
        });
    }
}
