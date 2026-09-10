<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GameListController;
use App\Http\Controllers\GameStatusController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ReleaseAlertController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
// use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
|
| Registro, inicio de sesión y consulta de la sesión actual.
|
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Se mantiene público para comprobar si existe una sesión activa.
Route::get('/user', [UserController::class, 'show']);

/*
|--------------------------------------------------------------------------
| PUBLIC USER PROFILES
|--------------------------------------------------------------------------
|
| Información pública de los usuarios.
|
*/

// Perfil público del usuario
Route::get(
    '/users/{username}',
    [PublicProfileController::class, 'show']);

// Listas públicas del usuario
Route::get(
    '/users/{username}/lists/{gameList}',
    [PublicProfileController::class, 'showList']
);

/*
|--------------------------------------------------------------------------
| REVIEWS - PUBLIC
|--------------------------------------------------------------------------
|
| Cualquier visitante puede consultar las reviews.
| Crear, editar y eliminar requiere autenticación.
|
*/
Route::apiResource('reviews', ReviewController::class)
    ->only(['index', 'show']);

/*
|--------------------------------------------------------------------------
| COMMUNITY - PUBLIC
|--------------------------------------------------------------------------
|
| La comunidad puede consultarse sin iniciar sesión.
|
*/

// Página principal: latest activity / latest discussions.
Route::get(
    '/community',
    [CommunityController::class, 'index']
);

// Listas públicas creadas por los usuarios.
Route::get(
    '/community/lists',
    [CommunityController::class, 'lists']
);

// Threads generales, no asociados a ningún videojuego.
Route::get(
    '/community/general',
    [ThreadController::class, 'general']
);

// Threads asociados a un videojuego mediante su RAWG ID.
Route::get(
    '/community/games/{rawgId}',
    [ThreadController::class, 'game']
);

// Detalle de un thread y sus posts.
Route::get(
    '/community/threads/{thread}',
    [ThreadController::class, 'show']
);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
|
| Todas las rutas de este grupo requieren una sesión válida mediante
| Laravel Sanctum.
|
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATION
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | PRIVATE PROFILE
    |--------------------------------------------------------------------------
    |
    | Perfil privado y configuración de la cuenta.
    |
    */
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put(
        '/profile',
        [ProfileController::class, 'update']
    );
    Route::put(
        '/profile/password',
        [ProfileController::class, 'updatePassword']
    );

    /*
    |--------------------------------------------------------------------------
    | GAME FAVORITES & RELEASE ALERTS
    |--------------------------------------------------------------------------
    */
    Route::apiResource('favorites', FavoriteController::class)->only([
        'index',
        'store',
        'destroy',
    ]);
    Route::apiResource('release-alerts', ReleaseAlertController::class)->only([
        'index',
        'store',
        'destroy',
    ]);

    /*
    |--------------------------------------------------------------------------
    | ENTITY FAVORITES
    |--------------------------------------------------------------------------
    |
    | Publishers, developers y creators favoritos del usuario.
    |
    */
    Route::apiResource('publishers', PublisherController::class)->only([
        'index',
        'store',
        'destroy',
    ]);
    Route::apiResource('developers', DeveloperController::class)->only([
        'index',
        'store',
        'destroy',
    ]);
    Route::apiResource('creators', CreatorController::class)->only([
        'index',
        'store',
        'destroy',
    ]);

    /*
    |--------------------------------------------------------------------------
    | USER GAME DATA
    |--------------------------------------------------------------------------
    |
    | Juegos guardados localmente, estados y listas personalizadas.
    |
    */
    Route::apiResource('game-statuses', GameStatusController::class)->only([
        'index',
        'store',
        'destroy',
    ]);
    Route::apiResource('game-lists', GameListController::class)->only([
        'index',
        'store',
        'show',
        'destroy',
    ]);

    // Añadir un juego a una lista personalizada.
    Route::post(
        '/game-lists/{gameList}/games',
        [GameListController::class, 'addGame']
    );

    // Eliminar un juego de una lista mediante su RAWG ID.
    Route::delete(
        '/game-lists/{gameList}/games/{rawgId}',
        [GameListController::class, 'removeGame']
    );

    /*
    |--------------------------------------------------------------------------
    | REVIEWS - AUTHENTICATED ACTIONS
    |--------------------------------------------------------------------------
    |
    | Solo usuarios autenticados pueden crear, editar o eliminar reviews.
    |
    */
    Route::apiResource('reviews', ReviewController::class)
        ->only(['store', 'update', 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | COMMUNITY - THREADS
    |--------------------------------------------------------------------------
    |
    | Solo usuarios autenticados pueden crear, editar o eliminar threads.
    |
    */

    // Crear un thread general o asociado a un videojuego.
    Route::post(
        '/community/threads',
        [ThreadController::class, 'store']
    );

    // Editar el título de un thread propio.
    Route::put(
        '/community/threads/{thread}',
        [ThreadController::class, 'update']
    );

    // Eliminar un thread propio.
    Route::delete(
        '/community/threads/{thread}',
        [ThreadController::class, 'destroy']
    );

    /*
    |--------------------------------------------------------------------------
    | COMMUNITY - POSTS / REPLIES
    |--------------------------------------------------------------------------
    */

    // Responder a un thread.
    Route::post(
        '/community/threads/{thread}/posts',
        [PostController::class, 'store']
    );

    // Editar un post propio.
    Route::put(
        '/community/posts/{post}',
        [PostController::class, 'update']
    );

    // Eliminar un post propio.
    Route::delete(
        '/community/posts/{post}',
        [PostController::class, 'destroy']
    );

});
