<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\UpdateThreadRequest;
use App\Models\Game;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThreadController extends Controller
{
    /**
     * Threads generales.
     */
    public function general()
    {
        $threads = Thread::whereNull('game_id')
            ->with([
                'user:id,username',
            ])
            ->withCount('posts')
            ->latest('updated_at')
            ->get();

        $threads->each(function ($thread) {
            $thread->replies_count = max(
                $thread->posts_count - 1,
                0
            );
        });

        return response()->json($threads);
    }

    /**
     * Threads de un juego concreto.
     *
     * El foro puede existir conceptualmente aunque el juego
     * todavía no esté guardado en MySQL.
     */
    public function game(int $rawgId)
    {
        $game = Game::ofRawgId($rawgId)->first();

        if (! $game) {
            return response()->json([]);
        }

        $threads = $game->threads()
            ->with([
                'user:id,username',
            ])
            ->withCount('posts')
            ->latest('updated_at')
            ->get();

        $threads->each(function ($thread) {
            $thread->replies_count = max(
                $thread->posts_count - 1,
                0
            );
        });

        return response()->json($threads);
    }

    /**
     * Ver un thread completo con todos sus posts.
     */
    public function show(Thread $thread)
    {
        $thread->load([
            'user:id,username',

            'game:id,rawg_id,name,image',

            'posts' => function ($query) {
                $query
                    ->with('user:id,username')
                    ->oldest();
            },
        ]);

        $thread->loadCount('posts');

        $thread->replies_count = max(
            $thread->posts_count - 1,
            0
        );

        return response()->json($thread);
    }

    /**
     * Crear un thread general o de un juego.
     *
     * También crea el primer Post.
     */
    public function store(StoreThreadRequest $request)
    {
        $data = $request->validated();

        $thread = DB::transaction(
            function () use ($request, $data) {

                $gameId = null;

                /*
                 * Si llega rawg_id, es un thread perteneciente
                 * al foro de un videojuego.
                 */
                if (isset($data['rawg_id'])) {

                    $game = Game::updateOrCreate(
                        [
                            'rawg_id' => $data['rawg_id'],
                        ],
                        [
                            'name' => $data['name'],
                            'release_date' => $data['release_date'] ?? null,
                            'image' => $data['image'] ?? null,
                        ],
                    );

                    $gameId = $game->id;
                }

                /*
                 * Crear thread.
                 */
                $thread = $request->user()
                    ->threads()
                    ->create([
                        'game_id' => $gameId,
                        'title' => $data['title'],
                    ]);

                /*
                 * El mensaje inicial también es un Post.
                 */
                $thread->posts()->create([
                    'user_id' => $request->user()->id,
                    'body' => $data['body'],
                ]);

                return $thread;
            },
        );

        return response()->json([
            'message' => 'Thread created successfully',

            'thread' => $thread->load([
                'user:id,username',
                'game:id,rawg_id,name,image',
                'posts.user:id,username',
            ]),
        ], 201);
    }

    /**
     * Editar título.
     */
    public function update(
        UpdateThreadRequest $request,
        Thread $thread
    ) {
        if (
            $thread->user_id !==
            $request->user()->id
        ) {
            abort(403);
        }

        $thread->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Thread updated successfully',
            'thread' => $thread,
        ]);
    }

    /**
     * Eliminar thread.
     *
     * Si posts.thread_id tiene cascadeOnDelete(),
     * sus posts desaparecerán automáticamente.
     */
    public function destroy(
        Request $request,
        Thread $thread
    ) {
        if (
            $thread->user_id !==
            $request->user()->id
        ) {
            abort(403);
        }

        $thread->delete();

        return response()->json([
            'message' => 'Thread deleted successfully',
        ]);
    }
}
