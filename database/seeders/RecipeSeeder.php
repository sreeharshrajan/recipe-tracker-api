<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $json = File::get(database_path('data/recipe.json'));
        $data = json_decode($json, true);

        foreach ($data as $recipe) {
            Recipe::create([
                'name' => $recipe['name'],
                'ingredients' => is_array($recipe['ingredients']) ? implode(',', $recipe['ingredients']) : $recipe['ingredients'],
                'prep_time' => $recipe['prep_time'],
                'cook_time' => $recipe['cook_time'],
                'difficulty' => $recipe['difficulty'],
                'description' => $recipe['description']
            ]);
        }
    }
}
