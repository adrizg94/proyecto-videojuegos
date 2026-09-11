<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function favoriteGames()
    {
        return $this->belongsToMany(Game::class, 'favorites')
            ->withTimestamps();
    }

    public function releaseAlertGames()
    {
        return $this->belongsToMany(Game::class, 'release_alerts')->withTimestamps();
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
        return $this->hasMany(GameList::class);
    }

    public function publishers()
    {
        return $this->belongsToMany(Publisher::class, 'user_publishers')->withTimestamps();
    }

    public function developers()
    {
        return $this->belongsToMany(Developer::class, 'user_developers')->withTimestamps();
    }

    public function creators()
    {
        return $this->belongsToMany(Creator::class, 'user_creators')->withTimestamps();
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
