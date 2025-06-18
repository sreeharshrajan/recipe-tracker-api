<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Recipe::all()->toJson();
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        //
    }

    public function searchByTimeAndIngredients(Request $request)
    {
        $request->validate([
            'ingredients' => 'required|array',
            'min_time' => 'required|integer',
            'max_time' => 'required|integer',
        ]);

        $ingredients = $request->input('ingredients');
        $min = $request->input('min_time');
        $max = $request->input('max_time');

        $recipes = Recipe::all()->filter(function ($recipe) use ($ingredients, $min, $max) {
            $recipeIngredients = array_map('trim', explode(',', strtolower($recipe->ingredients)));
            $hasAllIngredients = collect($ingredients)->every(fn($i) => in_array(strtolower($i), $recipeIngredients));
            $totalTime = $recipe->prep_time + $recipe->cook_time;

            return $hasAllIngredients && $totalTime >= $min && $totalTime <= $max;
        });

        return RecipeResource::collection($recipes);
    }
}
