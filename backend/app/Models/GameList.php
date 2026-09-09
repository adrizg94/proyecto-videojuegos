<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GameList extends Model
{
    protected $fillable = ['user_id', 'title'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_list_games')->withTimestamps();
    }

    public function scopeOfTitle(Builder $query, int $title)
    {
        return $query->where('title', $title);
    }
}
