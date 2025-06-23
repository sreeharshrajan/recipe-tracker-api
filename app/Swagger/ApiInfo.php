<?php
namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Recipe Tracker API",
 *     description="This is the API documentation for the Recipe Tracker project.",
 *     @OA\Contact(
 *         email="sreeharshkrajan@gmail.com"
 *     )
 * )
 *  * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class ApiInfo
{
}
