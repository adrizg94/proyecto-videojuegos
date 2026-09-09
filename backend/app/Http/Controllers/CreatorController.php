<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreatorRequest;
use App\Models\Creator;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->creators()->get();
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
    public function store(StoreCreatorRequest $request)
    {
        $data = $request->validated();

        $creator = Creator::updateOrCreate(
            [
                'name' => $data['name'],
            ],
            [
                'name' => $data['name'],
                'image' => $data['image'] ?? null,
            ],
        );

        $request->user()->creators()->syncWithoutDetaching([$creator->id]);

        return response()->json([
            'message' => 'Creator added to favorites',
            'creator' => $creator,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Creator $creator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Creator $creator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Creator $creator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $name, Request $request)
    {
        $creator = Creator::ofName($name)->firstOrFail();

        $request->user()
            ->creators()
            ->detach($creator->id);

        return response()->json([
            'message' => 'Creator removed from favorites',
        ]);
    }
}
