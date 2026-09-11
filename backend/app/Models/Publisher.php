<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $fillable = ['rawg_id', 'name', 'image'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_publishers')->withTimestamps();
    }

    public function scopeOfName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }
}
