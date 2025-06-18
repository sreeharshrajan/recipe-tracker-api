<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Http\Resources\RecipeResource;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Schema(
 *     schema="Recipe",
 *     type="object",
 *     title="Recipe",
 *     description="Recipe model schema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Spaghetti Bolognese"),
 *     @OA\Property(
 *         property="ingredients",
 *         type="string",
 *         example="pasta, ground beef, tomatoes, onions, garlic, olive oil, salt, pepper"
 *     ),
 *     @OA\Property(property="prep_time", type="integer", example=15, description="Preparation time in minutes"),
 *     @OA\Property(property="cook_time", type="integer", example=30, description="Cooking time in minutes"),
 *     @OA\Property(
 *         property="difficulty",
 *         type="string",
 *         enum={"easy", "medium", "hard"},
 *         example="medium"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         example="A classic Italian pasta dish made with ground beef and tomato sauce."
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-06-01T10:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-06-10T15:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         example=null
 *     )
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

    /**
     * @OA\Get(
     *     path="/api/recipes/difficulty/{level}",
     *     summary="Filter recipes by difficulty",
     *     tags={"Recipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="level",
     *         in="path",
     *         required=true,
     *         description="Difficulty level (easy, medium, hard)",
     *         @OA\Schema(type="string", enum={"easy", "medium", "hard"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filtered recipes",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Recipes fetched successfully."),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Recipe"))
     *         )
     *     ),
     *     @OA\Response(response=422, description="Invalid difficulty level"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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


    /**
     * @OA\Post(
     *     path="/api/recipes",
     *     summary="Create a new recipe",
     *     tags={"Recipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "ingredients", "description", "prep_time", "cook_time", "difficulty"},
     *             @OA\Property(property="name", type="string", example="Spaghetti Carbonara"),
     *             @OA\Property(property="ingredients", type="string", example="Spaghetti, Eggs, Bacon"),
     *             @OA\Property(property="description", type="string", example="Boil pasta. Fry bacon. Mix with eggs."),
     *             @OA\Property(property="prep_time", type="integer", example=10),
     *             @OA\Property(property="cook_time", type="integer", example=20),
     *             @OA\Property(property="difficulty", type="string", enum={"easy", "medium", "hard"})
     *         )
     *     ),
     *     @OA\Response(response=201, description="Recipe created successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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


    /**
     * @OA\Put(
     *     path="/api/recipes/{id}",
     *     summary="Update an existing recipe",
     *     tags={"Recipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="ingredients", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="prep_time", type="integer"),
     *             @OA\Property(property="cook_time", type="integer"),
     *             @OA\Property(property="difficulty", type="string", enum={"easy", "medium", "hard"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Recipe updated successfully"),
     *     @OA\Response(response=404, description="Recipe not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/recipes/{id}",
     *     summary="Delete a recipe",
     *     tags={"Recipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Recipe deleted successfully"),
     *     @OA\Response(response=404, description="Recipe not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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

            return response()->json([
                'status' => true,
                'message' => 'Recipe deleted successfully.',
                'data' => null
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete recipe.'
            ], 500);
        }
    }
}
