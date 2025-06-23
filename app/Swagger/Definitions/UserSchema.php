<?php

namespace App\Swagger\Definitions;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Jane Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="jane@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example="2024-06-01T12:34:56Z"),
 *     @OA\Property(property="password", type="string", format="password", writeOnly=true, example="hashed_password_string"),
 *     @OA\Property(property="remember_token", type="string", nullable=true, example="Yx8kP0s7w9bAx01Az"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-10T12:00:00Z")
 * )
 */
class UserSchema {}
