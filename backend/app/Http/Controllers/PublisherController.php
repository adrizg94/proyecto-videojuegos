<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublisherRequest;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->publishers()->get();
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
    public function store(StorePublisherRequest $request)
    {
        $data = $request->validated();

        $publisher = Publisher::updateOrCreate(
            [
                'rawg_id' => $data['rawg_id'],
            ],
            [
                'name' => $data['name'],
                'image' => $data['image'] ?? '/images/no-company.svg',
            ],
        );

        $request->user()->publishers()->syncWithoutDetaching([$publisher->id]);

        return response()->json([
            'message' => 'Publisher added to favorites',
            'publisher' => $publisher,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $name, Request $request)
    {
        $publisher = Publisher::ofName($name)->firstOrFail();

        $request->user()
            ->publishers()
            ->detach($publisher->id);

        return response()->json([
            'message' => 'Publisher removed from favorites',
        ]);
    }
}
