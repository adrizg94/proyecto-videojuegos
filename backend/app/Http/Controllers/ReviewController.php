<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
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
            return response()->json([]);
        }

        return $game->reviews()
            ->with('user:id,username')
            ->latest()
            ->get();
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
    public function store(StoreReviewRequest $request)
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
        $review = $request->user()
            ->reviews()
            ->create([
                'game_id' => $game->id,
                'rating' => $data['rating'],
                'review' => $data['review'],
            ]);

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return $review;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        // Controlamos que un usuario no pueda editar las reviews de otro usuario
        if ($review->user_id !== $request->user()->id) {
            abort(403);
        }

        $review->update($request->validated());

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Review $review)
    {
        // Controlamos que un usuario no pueda editar las reviews de otro usuario
        if ($review->user_id !== $request->user()->id) {
            abort(403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }
}
