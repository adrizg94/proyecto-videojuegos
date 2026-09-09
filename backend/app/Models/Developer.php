<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class Developer extends Model
{
    protected $fillable = ['name', 'image'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_developers');
    }

    public function scopeOfName(Builder $query, string $name) {
        return $query->where('name', $name);
    }
}
