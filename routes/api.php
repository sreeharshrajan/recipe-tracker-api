<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;


//General Routes (Unauthenticated Routes)
Route::post('/login', [AuthController::class, 'login']);

//Protected Routes (Authenticated Routes)
Route::middleware('auth:sanctum')->group(function () {
    // User Routes
    Route::get('/user', [AuthController::class, 'getUserDetails']);

    // Recipe Routes
    Route::prefix('recipes')->group(function () {
        Route::get('/', [RecipeController::class, 'index']);
        Route::get('/search', [RecipeController::class, 'searchByTimeAndIngredients']);
        Route::get('/difficulty/{level}', [RecipeController::class, 'filterByDifficulty']);
        Route::get('/{id}', [RecipeController::class, 'show']);
        Route::post('/', [RecipeController::class, 'store']);
        Route::put('/{id}', [RecipeController::class, 'update']);
        Route::delete('/{id}', [RecipeController::class, 'destroy']);
    });
});
