<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Recipe Tracker API",
 *     description="This is the API documentation for the Recipe Tracker project.",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 *  * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller {}
