<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'ingredients' => implode(', ', $this->faker->words(5)),
            'prep_time' => rand(5, 20),
            'cook_time' => rand(10, 40),
            'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'description' => $this->faker->paragraph
        ];
    }
}
