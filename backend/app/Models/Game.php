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

    public function scopeOfRawgId(Builder $query, int $rawgId) {
        return $query->where('rawg_id', $rawgId);
    }
}
