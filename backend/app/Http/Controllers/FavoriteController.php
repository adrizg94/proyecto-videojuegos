<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Game;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Sin middleware, cambiar
        $favorites = Favorite::all();
        return $favorites;





        // $favorites = $request->user()
        //     ->favoriteGames()
        //     ->pluck('rawg_id');

        // return response()->json($favorites);

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
    public function store(Request $request)
    {
        $data = $request->validate([
            'rawg_id' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'background_image' => ['nullable', 'string'],
        ]);

        // $game = Game::firstOrCreate(
        //     [
        //         'rawg_id' => $data['rawg_id'],
        //     ],
        //     [
        //         'name' => $data['name'],
        //         'background_image' => $data['background_image'] ?? null,
        //     ]
        // );

        // $request->user()
        //     ->favoriteGames()
        //     ->syncWithoutDetaching([$game->id]);

        return response()->json([
            'message' => 'Game added to favorites',
            // 'game' => $game,
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
    public function destroy(Request $request, int $rawgId)
    {
        $game = Game::where('rawg_id', $rawgId)->firstOrFail();

        $request->user()
            ->favoriteGames()
            ->detach($game->id);

        return response()->json([
            'message' => 'Game removed from favorites',
        ]);
    }
}
