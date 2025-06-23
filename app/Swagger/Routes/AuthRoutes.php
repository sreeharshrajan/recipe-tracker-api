<?php


namespace App\Swagger\Routes;


use OpenApi\Annotations as OA;


/**
 * @OA\Post(
 *     path="/api/login",
 *     summary="Login user and get token",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 example="test@example.com"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 example="test@123"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Login successful."),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="user", type="object"),
 *                 @OA\Property(property="token", type="string", example="1|abc123tokenexample")
 *             )
 *         )
 *     ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Validation error."),
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(
 *                      property="email",
 *                      type="array",
 *                      @OA\Items(type="string", example="The email field is required.")
 *                  )
 *              )
 *          )
 *      ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid credentials."),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     )
 * )
 */
class AuthRoutes
{
}
