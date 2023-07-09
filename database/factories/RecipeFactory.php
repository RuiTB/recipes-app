<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->userName(),
            'description' => fake()->realText(),
            'steps' => fake()->randomNumber(2),
            'time' => fake()->randomNumber(2),
            'size' => fake()->colorName(),
            'user_id' => User::inRandomOrder()->value('id')
        ];
    }
}
