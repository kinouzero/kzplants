<?php

namespace App\Policies;

use App\Models\Plant;
use App\Models\User;

class PlantPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Plant $plant): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($plant->created_by === $user->id) {
            return true;
        }

        return $plant->dashboards()->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Plant $plant): bool
    {
        return $user->isAdmin() || $plant->created_by === $user->id;
    }

    public function delete(User $user, Plant $plant): bool
    {
        return $user->isAdmin() || $plant->created_by === $user->id;
    }
}
