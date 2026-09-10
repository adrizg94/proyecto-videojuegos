<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    protected $fillable = ['rawg_id', 'name', 'image'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_creators');
    }

    public function scopeOfName(Builder $query, string $name)
    {
        return $query->where('name', $name);
    }
}
