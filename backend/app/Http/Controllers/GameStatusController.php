<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameStatusRequest;
use App\Models\Game;
use App\Models\GameStatus;
use Illuminate\Http\Request;

class GameStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'rawg_id' => ['required', 'integer'],
        ]);

        $game = Game::ofRawgId($data['rawg_id'])->first();

        if (! $game) {
            return response()->json(null);
        }

        return $game->gameStatuses()
            ->where('user_id', $request->user()->id)
            ->first();

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
    public function store(StoreGameStatusRequest $request)
    {
        $data = $request->validated();

        // Creamos el juego si no existe en la base de datos
        $game = Game::updateOrCreate(
            [
                'rawg_id' => $data['rawg_id'],
            ],
            [
                'name' => $data['name'],
                'release_date' => $data['release_date'] ?? null,
                'image' => $data['image'] ?? '/images/no-game.svg',
            ]
        );

        // El user_id se asigna automáticamente mediante las relaciones
        $gameStatus = GameStatus::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'game_id' => $game->id,
            ],
            [
                'status' => $data['status'],
            ]
        );

        return response()->json([
            'message' => 'Status saved successfully',
            'game_status' => $gameStatus,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GameStatus $gameStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GameStatus $gameStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreGameStatusRequest $request, GameStatus $gameStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, GameStatus $gameStatus)
    {
        // Comprobamos que el status pertenece al usuario autenticado
        if ($gameStatus->user_id !== $request->user()->id) {
            abort(403);
        }

        $gameStatus->delete();

        return response()->json([
            'message' => 'Status deleted successfully',
        ]);
    }
}
