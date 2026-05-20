<?php

namespace App\Policies;

use App\Models\Preference;
use App\Models\User;

class PreferencePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Preference $preference): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Preference $preference): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Preference $preference): bool
    {
        return $user->isAdmin();
    }
}
