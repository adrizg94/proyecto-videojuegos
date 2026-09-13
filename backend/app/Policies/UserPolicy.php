<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $authenticatedUser): bool
    {
        return $authenticatedUser->is_admin;
    }

    public function view(User $authenticatedUser, User $user): bool
    {
        return $authenticatedUser->is_admin;
    }

    public function create(User $authenticatedUser): bool
    {
        return $authenticatedUser->is_admin;
    }

    public function update(User $authenticatedUser, User $user): bool
    {
        return $authenticatedUser->is_admin;
    }

    public function delete(User $authenticatedUser, User $user): bool
    {
        return $authenticatedUser->is_admin
            && ! $authenticatedUser->is($user);
    }

    public function deleteAny(User $authenticatedUser): bool
    {
        return $authenticatedUser->is_admin;
    }
}
