<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'email', 'password', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
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
            'is_admin' => 'boolean',
        ];
    }

    public function favoriteGames()
    {
        return $this->belongsToMany(Game::class, 'favorites')
            ->withTimestamps();
    }

    public function releaseAlertGames()
    {
        return $this->belongsToMany(Game::class, 'release_alerts')->withPivot('notified_at')->withTimestamps();
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

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return 'https://ui-avatars.com/api/?'
            .http_build_query([
                'name' => $this->username,
                'background' => '5600c5',
                'color' => 'ffffff',
                'bold' => 'true',
            ]);
    }
}
