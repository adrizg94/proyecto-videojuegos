<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Publisher;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return Developer::all();
    }
}
