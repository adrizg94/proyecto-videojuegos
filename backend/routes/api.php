<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\TestFavController;
use App\Models\TestFav;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{rawgId}', [FavoriteController::class, 'destroy']);
});

// Sin middleware, cambiar
Route::resource('favorites', FavoriteController::class);
Route::resource('test_favs', TestFavController::class);
// Route::get('/favorites', [FavoriteController::class, 'index']);
// Route::post('/favorites', [FavoriteController::class, 'store']);
// Route::delete('/favorites/{rawgId}', [FavoriteController::class, 'destroy']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
