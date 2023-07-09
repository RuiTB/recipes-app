<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\BookmarkController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user/{user}', function (User $user) {
    return $user;
});

// Register route
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Login route
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('bookmarks')->group(function () {
        // Get all bookmarks
        Route::get('/', [BookmarkController::class, 'index'])->name('bookmarks.index');

        // Create a bookmark
        Route::post('/', [BookmarkController::class, 'store'])->name('bookmarks.store');

        // Get a specific bookmark
        Route::get('/{bookmark}', [BookmarkController::class, 'show'])->name('bookmarks.show');

        // Update a bookmark
        Route::put('/{bookmark}', [BookmarkController::class, 'update'])->name('bookmarks.update');

        // Delete a bookmark
        Route::delete('/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
    });
});


Route::prefix('recipes')->group(function () {
    // Get all recipes
    Route::get('/', [RecipeController::class, 'index'])->name('recipes.index');

    // Get a specific recipe
    Route::get('/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');

    Route::middleware('auth:sanctum')->group(function () {
        // Create a recipe
        Route::post('/', [RecipeController::class, 'store'])->name('recipes.store');

        // Comment on a recipe
        Route::post('{recipe}/comments', [RecipeController::class, 'comment'])->name('recipes.comments.store');

        // Update a recipe
        Route::put('{recipe}', [RecipeController::class, 'update'])->name('recipes.update');

        // Delete a recipe
        Route::delete('{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    });
});


Route::prefix('ingredients')->group(function () {
    // Public routes
    Route::get('/', [IngredientController::class, 'index'])->name('ingredients.index');
    Route::get('/{ingredient}/recipes', [IngredientController::class, 'recipes'])->name('ingredients.recipes');

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [IngredientController::class, 'store'])->name('ingredients.store');
    });
});
