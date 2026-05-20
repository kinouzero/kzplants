<?php

namespace App\Policies;

use App\Models\Dashboard;
use App\Models\User;

class DashboardPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Dashboard $dashboard): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $dashboard->users()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Dashboard $dashboard): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $dashboard->users()->wherePivot('creator', true)->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, Dashboard $dashboard): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $dashboard->users()->wherePivot('creator', true)->where('user_id', $user->id)->exists();
    }
}
