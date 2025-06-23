<?php

namespace App\Swagger\Routes;

use OpenApi\Annotations as OA;


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
 * 
 * 
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
 * 
 * 
 *
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
 * 
 * 
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
 * 
 * 
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
 * 
 * 
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
 * 
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
class RecipeRoutes
{
}
