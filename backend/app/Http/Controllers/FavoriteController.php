<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameRequest;
use App\Models\Game;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->favoriteGames()->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request)
    {
        $data = $request->validated();

        // Busca si existe el juego antes de crearlo en MySQL, busca por rawg_id,
        // si no existe usa los campos name y background_image para crearlo,
        // si existe actualiza los datos, ya que los datos de rawg podrían cambiar.
        // $game = Game::updateOrCreate($request->validated());
        $game = Game::updateOrCreate(
            [
                'rawg_id' => $data['rawg_id'],
            ],
            [
                'name' => $data['name'],
                'image' => $data['image'] ?? '/images/no-game.svg',
                'release_date' => $data['release_date'] ?? null,
            ]
        );

        // Crea la relación entre games y users en favorites, en este caso
        // relaciona con el id 1 de usuarios para pruebas
        // $game->favoritedByUsers()
        //     ->syncWithoutDetaching([1]);
        $request->user()
            ->favoriteGames()
            ->syncWithoutDetaching([$game->id]);

        return response()->json([
            'message' => 'Game added to favorites',
            'game' => $game,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Request $request, int $rawgId)
    public function destroy(int $rawgId, Request $request)
    {
        $game = Game::ofRawgId($rawgId)->firstOrFail();

        $request->user()
            ->favoriteGames()
            ->detach($game->id);

        return response()->json([
            'message' => 'Game removed from favorites',
        ]);
    }
}
