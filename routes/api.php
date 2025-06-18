<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // User Routes
    Route::get('/user', [AuthController::class, 'getUserDetails']);
    // Recipe Routes
    Route::get('/recipes', [RecipeController::class, 'index']);
    Route::get('/recipes/search', [RecipeController::class, 'searchByTimeAndIngredients']);
    Route::get('/recipes/difficulty/{level}', [RecipeController::class, 'filterByDifficulty']);
    Route::get('/recipes/{id}', [RecipeController::class, 'show']);
});
