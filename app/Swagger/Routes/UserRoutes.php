<?php

namespace App\Swagger\Routes;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/user",
 *     summary="Get authenticated user details",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},  
 *     @OA\Response(
 *         response=200,
 *         description="User details fetched successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="User details retrieved successfully."),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="john@example.com"),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-10T12:00:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthenticated.")
 * )
 */
class UserRoutes
{
}