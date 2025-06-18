<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Resources\RecipeResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Schema(
 *     schema="Recipe",
 *     type="object",
 *     title="Recipe",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Spaghetti Bolognese"),
 *     @OA\Property(property="ingredients", type="string", example="pasta, beef, tomato"),
 *     @OA\Property(property="instructions", type="string", example="Boil pasta. Cook beef. Mix with sauce."),
 *     @OA\Property(property="prep_time", type="integer", example=15),
 *     @OA\Property(property="cook_time", type="integer", example=30),
 *     @OA\Property(property="difficulty", type="string", example="medium"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-10T15:00:00Z")
 * )
 */

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/recipes",
     *     summary="Get all recipes",
     *     tags={"Recipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of recipes",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Recipes fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="recipes",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Recipe")
     *                 )
     *             )
     *         )
     *     )
     * )
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
    /**
     * @OA\Get(
     *     path="/api/recipes/{id}",
     *     summary="Get a specific recipe by ID",
     *     tags={"Recipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the recipe",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recipe found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Recipe fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="recipe", ref="#/components/schemas/Recipe")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Recipe not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
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

    /**
     * @OA\Get(
     *     path="/api/recipes/search",
     *     summary="Search recipes by ingredients and time range",
     *     tags={"Recipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="ingredients[]",
     *         in="query",
     *         required=true,
     *         description="List of ingredients",
     *         @OA\Schema(type="array", @OA\Items(type="string", example="chicken"))
     *     ),
     *     @OA\Parameter(
     *         name="min_time",
     *         in="query",
     *         required=true,
     *         description="Minimum total time (prep + cook)",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="max_time",
     *         in="query",
     *         required=true,
     *         description="Maximum total time (prep + cook)",
     *         @OA\Schema(type="integer", example=60)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recipes matching criteria",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Recipes fetched successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Recipe")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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
}
