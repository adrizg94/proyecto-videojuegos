<?php

namespace App\Http\Controllers;

use App\Models\TestFav;
use Illuminate\Http\Request;

class TestFavController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favorites = TestFav::All();

        return $favorites;
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
    public function store(Request $request)
    {
        $favorite = TestFav::create([
            'rawg_id' => $request->rawg_id,
        ]);

        return response()->json($favorite, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TestFav $testFav)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TestFav $testFav)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TestFav $testFav)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $rawg_id)
    {
        $favorite = TestFav::where('rawg_id', $rawg_id)->firstOrFail();

        $favorite->delete();

    }
}
