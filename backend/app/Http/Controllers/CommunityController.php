<?php

namespace App\Http\Controllers;

use App\Models\GameList;
use App\Models\Thread;

class CommunityController extends Controller
{
    /**
     * Datos para la página principal de Community.
     */
    public function index()
    {
        $latestThreads = Thread::with([
            'user:id,username',
            'game:id,rawg_id,name,image',
        ])
            ->withCount('posts')
            ->latest('updated_at')
            ->limit(10)
            ->get();

        // El primer post es el mensaje inicial,
        // por lo que no cuenta como reply.
        $latestThreads->each(function ($thread) {
            $thread->replies_count = max(
                $thread->posts_count - 1,
                0
            );
        });

        return response()->json([
            'latest_threads' => $latestThreads,
        ]);
    }

    /**
     * Listas creadas por la comunidad.
     * Por ahora todas las listas son públicas.
     */
    public function lists()
    {
        $lists = GameList::with([
            'user:id,username',
        ])
            ->withCount('games')
            ->latest()
            ->get();

        return response()->json($lists);
    }
}
