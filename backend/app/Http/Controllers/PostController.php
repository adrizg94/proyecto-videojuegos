<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Responder a un thread.
     */
    public function store(
        StorePostRequest $request,
        Thread $thread
    ) {
        $data = $request->validated();

        $post = $thread->posts()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        /*
         * Hace que el thread vuelva arriba
         * en "Latest activity".
         */
        $thread->touch();

        return response()->json([
            'message' => 'Reply published successfully',

            'post' => $post->load(
                'user:id,username'
            ),
        ], 201);
    }

    /**
     * Editar un post propio.
     */
    public function update(
        UpdatePostRequest $request,
        Post $post
    ) {
        if (
            $post->user_id !==
            $request->user()->id
        ) {
            abort(403);
        }

        $post->update(
            $request->validated()
        );

        $post->thread->touch();

        return response()->json([
            'message' => 'Post updated successfully',

            'post' => $post->load(
                'user:id,username'
            ),
        ]);
    }

    /**
     * Eliminar respuesta propia.
     */
    public function destroy(
        Request $request,
        Post $post
    ) {
        if (
            $post->user_id !==
            $request->user()->id
        ) {
            abort(403);
        }

        $thread = $post->thread;

        /*
         * No permitimos borrar el Post inicial por separado.
         * Para eso debe borrarse el Thread completo.
         */
        $firstPostId = $thread
            ->posts()
            ->oldest('id')
            ->value('id');

        if ($post->id === $firstPostId) {
            return response()->json([
                'message' => 'The first post cannot be deleted separately. Delete the thread instead.',
            ], 422);
        }

        $post->delete();

        $thread->touch();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
