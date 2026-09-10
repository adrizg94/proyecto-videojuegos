<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameListController;
use App\Http\Controllers\GameStatusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ReleaseAlertController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TestFavController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [UserController::class, 'show']);
Route::get(
    '/users/{username}',
    [PublicProfileController::class, 'show']);

Route::apiResource('reviews', ReviewController::class)
    ->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put(
        '/profile',
        [ProfileController::class, 'update']
    );
    Route::put(
        '/profile/password',
        [ProfileController::class, 'updatePassword']
    );

    Route::apiResource('favorites', FavoriteController::class);
    Route::apiResource('release-alerts', ReleaseAlertController::class);
    Route::apiResource('games', GameController::class);
    Route::apiResource('game-statuses', GameStatusController::class);
    Route::apiResource('game-lists', GameListController::class);
    Route::apiResource('publishers', PublisherController::class);
    Route::apiResource('developers', DeveloperController::class);
    Route::apiResource('creators', CreatorController::class);

    Route::apiResource('reviews', ReviewController::class)
        ->only(['store', 'update', 'destroy']);

    Route::post(
        '/game-lists/{gameList}/games',
        [GameListController::class, 'addGame']
    );

    Route::delete(
        '/game-lists/{gameList}/games/{rawgId}',
        [GameListController::class, 'removeGame']
    );

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
