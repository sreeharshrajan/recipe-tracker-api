<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Http\Resources\RecipeResource;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $recipes = Recipe::all();
            return response()->json([
                'status' => true,
                'data' => [
                    'recipes' => RecipeResource::collection($recipes)
                ],
                'message' => 'Recipes fetched successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch recipes.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => [
                    'recipe' => new RecipeResource($recipe)
                ],
                'message' => 'Recipe fetched successfully!'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Recipe not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch recipe.',
            ], 500);
        }
    }

    public function searchByTimeAndIngredients(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'ingredients' => 'required|array',
            'ingredients.*' => 'string',
            'min_time' => 'required|integer|min:0',
            'max_time' => 'required|integer|min:0|gte:min_time',
        ]);
        try {
            // Normalize inputs
            $inputIngredients = collect($validated['ingredients'])
                ->map(fn($i) => strtolower(trim($i)));

            $min = $validated['min_time'];
            $max = $validated['max_time'];

            // Get recipes in the specified time range
            $recipes = Recipe::all()->filter(function ($recipe) use ($min, $max) {
                $totalTime = $recipe->prep_time + $recipe->cook_time;
                return $totalTime >= $min && $totalTime <= $max;
            });

            // Step 2: Match ingredients (loose match using str_contains)
            $filtered = $recipes->filter(function ($recipe) use ($inputIngredients) {
                $recipeIngredientsStr = strtolower($recipe->ingredients);

                return $inputIngredients->every(function ($ingredient) use ($recipeIngredientsStr) {
                    return str_contains($recipeIngredientsStr, $ingredient);
                });
            });

            return response()->json([
                'status' => true,
                'data' => RecipeResource::collection($filtered),
                'message' => $filtered->isEmpty() ? 'No matching recipes found.' : 'Recipes fetched successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to search recipes.',
            ], 500);
        }
    }

    public function filterByDifficulty($level)
    {
        try {
            if (!in_array($level, ['easy', 'medium', 'hard'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid difficulty level.'
                ], 422);
            }

            $recipes = Recipe::where('difficulty', $level)->get();

            return response()->json([
                'status' => true,
                'message' => 'Recipes fetched successfully.',
                'data' => RecipeResource::collection($recipes)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch recipes.'
            ], 500);
        }
    }


    public function store(StoreRecipeRequest $request)
    {
        try {
            $recipe = Recipe::create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Recipe created successfully.',
                'data' => new RecipeResource($recipe)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to create recipe.'
            ], 500);
        }
    }


    public function update(UpdateRecipeRequest $request, $id)
    {
        try {
            $recipe = Recipe::find($id);
            if (!$recipe) {
                return response()->json([
                    'status' => false,
                    'message' => 'Recipe not found.'
                ], 404);
            }

            $recipe->update($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Recipe updated successfully.',
                'data' => new RecipeResource($recipe)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update recipe.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $recipe = Recipe::find($id);
            if (!$recipe) {
                return response()->json([
                    'status' => false,
                    'message' => 'Recipe not found.',
                    'data' => null
                ], 404);
            }

            $recipe->delete();

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete recipe.'
            ], 500);
        }
    }
}
