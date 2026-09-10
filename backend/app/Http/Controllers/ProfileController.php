<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
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

            'release_alerts' => $user
                ->releaseAlertGames()
                ->get(),

            'game_statuses' => $user
                ->gameStatuses()
                ->with('game')
                ->latest()
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

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return response()->json([
            'message' => 'Account information updated successfully',
            'user' => $user,
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $data = $request->validated();

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'message' => 'Password updated successfully',
        ]);
    }
}
