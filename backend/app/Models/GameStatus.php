<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameStatus extends Model
{
    protected $fillable = ['user_id', 'game_id', 'status'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
