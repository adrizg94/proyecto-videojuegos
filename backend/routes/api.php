<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TestFavController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [UserController::class, 'show']);

Route::apiResource('reviews', ReviewController::class)
    ->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('favorites', FavoriteController::class);
    Route::apiResource('games', GameController::class);

    Route::apiResource('reviews', ReviewController::class)
        ->only(['store', 'update', 'destroy']);
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/favorites', [FavoriteController::class, 'index']);
//     Route::post('/favorites', [FavoriteController::class, 'store']);
//     Route::delete('/favorites/{rawgId}', [FavoriteController::class, 'destroy']);
// });

// Sin middleware, cambiar
// Route::resource('test_favs', TestFavController::class);
// Route::get('/favorites', [FavoriteController::class, 'index']);
// Route::post('/favorites', [FavoriteController::class, 'store']);
// Route::delete('/favorites/{rawgId}', [FavoriteController::class, 'destroy']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
