<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeveloperRequest;
use App\Models\Developer;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->developers()->get();
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
    public function store(StoreDeveloperRequest $request)
    {
        $data = $request->validated();

        $developer = Developer::updateOrCreate(
            [
                'rawg_id' => $data['rawg_id'],
            ],
            [
                'name' => $data['name'],
                'image' => $data['image'] ?? '/images/no-company.svg',
            ],
        );

        $request->user()->developers()->syncWithoutDetaching([$developer->id]);

        return response()->json([
            'message' => 'Developers added to favorites',
            'developer' => $developer,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Developer $developer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Developer $developer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Developer $developer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $name, Request $request)
    {
        $developer = Developer::ofName($name)->firstOrFail();

        $request->user()
            ->developers()
            ->detach($developer->id);

        return response()->json([
            'message' => 'Developer removed from favorites',
        ]);
    }
}
