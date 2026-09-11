<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['rawg_id', 'name', 'release_date', 'image'];

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    public function scopeOfRawgId(Builder $query, int $rawgId): Builder
    {
        return $query->where('rawg_id', $rawgId);
    }

    public function releaseAlertForUsers()
    {
        return $this->belongsToMany(User::class, 'release_alerts')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function gameStatuses()
    {
        return $this->hasMany(GameStatus::class);
    }

    public function gameLists()
    {
        return $this->belongsToMany(GameList::class, 'game_list_games')->withTimestamps();
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
