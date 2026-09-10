<?php

namespace App\Http\Controllers;

use App\Models\GameList;
use App\Models\User;

class PublicProfileController extends Controller
{
    public function show(string $username)
    {
        $user = User::where('username', $username)
            ->firstOrFail();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'created_at' => $user->created_at,
            ],

            'favorite_games' => $user
                ->favoriteGames()
                ->get(),

            'favorite_publishers' => $user
                ->publishers()
                ->get(),

            'favorite_developers' => $user
                ->developers()
                ->get(),

            'favorite_creators' => $user
                ->creators()
                ->get(),

            'game_lists' => $user
                ->gameLists()
                ->withCount('games')
                ->latest()
                ->get(),

            'reviews' => $user
                ->reviews()
                ->with('game')
                ->latest()
                ->get(),
        ]);
    }

    public function showList(string $username, GameList $gameList)
    {
        $user = User::where('username', $username)->firstOrFail();

        if ($gameList->user_id !== $user->id) {
            abort(404);
        }

        $gameList->load('games');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
            ],
            'list' => $gameList,
        ]);
    }
}
