<?php

namespace App\Swagger\Definitions;


use OpenApi\Annotations as OA;


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
class RecipeSchema
{
}