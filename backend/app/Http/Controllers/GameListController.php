<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameListRequest;
use App\Http\Requests\StoreGameRequest;
use App\Models\Game;
use App\Models\GameList;
use Illuminate\Http\Request;

class GameListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $data = $request->validate([
        //     'title' => ['required', 'string'],
        // ]);

        // $user = $request->user();
        // $gameList = $user->gameLists()->where('title', $data['title'])->firstOrFail();

        // return $gameList->games()->get();

        $data = $request->validate([
            'rawg_id' => ['required', 'integer'],
        ]);

        return $request->user()
            ->gameLists()
            ->withExists([
                'games as contains_game' => function ($query) use ($data) {
                    $query->where('games.rawg_id', $data['rawg_id']);
                },
            ])
            ->get();

        // $game = Game::ofRawgId($data['rawg_id'])->first();

        // $gameLists = $user->gameLists()
        //     ->with('games')
        //     ->get();

        // $gameLists->each(function ($gameList) use ($game) {
        //     $gameList->contains_game = $game
        //         ? $gameList->games->contains('id', $game->id)
        //         : false;
        // });

        // return $gameLists;

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
    public function store(StoreGameListRequest $request)
    {
        // $data = $request->validated();

        // // Creamos el juego si no existe en la base de datos
        // $game = Game::updateOrCreate(
        //     [
        //         'rawg_id' => $data['rawg_id'],
        //     ],
        //     [
        //         'name' => $data['name'],
        //         'release_date' => $data['release_date'] ?? null,
        //         'image' => $data['image'] ?? null,
        //     ]
        // );

        // // El user_id se asigna automáticamente mediante las relaciones
        // $gameList = GameList::updateOrCreate(
        //     [
        //         'user_id' => $request->user()->id,
        //         'game_id' => $game->id,
        //     ],
        //     [
        //         'title' => $data['status'],
        //     ]
        // );

        // return response()->json([
        //     'message' => 'Status created successfully',
        //     'game_status' => $gameList,
        // ], 201);

        $data = $request->validated();

        $gameList = $request->user()
            ->gameLists()
            ->create([
                'title' => $data['title'],
            ]);

        return response()->json([
            'message' => 'Game list created successfully',
            'game_list' => $gameList,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, GameList $gameList)
    {
        if ($gameList->user_id !== $request->user()->id) {
            abort(403);
        }

        return $gameList->load('games');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GameList $gameList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GameList $gameList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, GameList $gameList)
    {
        if ($gameList->user_id !== $request->user()->id) {
            abort(403);
        }

        $gameList->delete();

        return response()->json([
            'message' => 'Game list deleted successfully',
        ]);
    }

    public function addGame(StoreGameRequest $request, GameList $gameList)
    {
        if ($gameList->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validated();

        $game = Game::updateOrCreate(
            ['rawg_id' => $data['rawg_id']],
            [
                'name' => $data['name'],
                'release_date' => $data['release_date'] ?? null,
                'image' => $data['image'] ?? '/images/no-game.svg',
            ]
        );

        $gameList->games()->syncWithoutDetaching([
            $game->id,
        ]);

        return response()->json([
            'message' => 'Game added to list successfully',
        ], 201);
    }

    public function removeGame(
        Request $request,
        GameList $gameList,
        int $rawgId
    ) {
        if ($gameList->user_id !== $request->user()->id) {
            abort(403);
        }

        $game = Game::ofRawgId($rawgId)->firstOrFail();

        $gameList->games()->detach($game->id);

        return response()->json([
            'message' => 'Game removed from list successfully',
        ]);
    }
}
