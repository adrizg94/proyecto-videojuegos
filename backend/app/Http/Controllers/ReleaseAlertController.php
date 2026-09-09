<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameRequest;
use App\Models\Game;
use Illuminate\Http\Request;

class ReleaseAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->ReleaseAlertGames()->get();
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

        $game = Game::updateOrCreate(
            [
                'rawg_id' => $data['rawg_id'],
            ],
            [
                'name' => $data['name'],
                'image' => $data['image'] ?? null,
                'release_date' => $data['release_date'] ?? null,
            ]
        );

        $request->user()
            ->releaseAlertGames()
            ->syncWithoutDetaching([$game->id]);

        return response()->json([
            'message' => 'Alert added succesfully',
            'game' => $game,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $rawgId, Request $request)
    {
        $game = Game::ofRawgId($rawgId)->firstOrFail();

        $request->user()
            ->releaseAlertGames()
            ->detach($game->id);

        return response()->json([
            'message' => 'Alert removed succesfully',
        ]);
    }
}
