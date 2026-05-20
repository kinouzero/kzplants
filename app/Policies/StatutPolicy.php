<?php

namespace App\Policies;

use App\Models\Statut;
use App\Models\User;

class StatutPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Statut $statut): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Statut $statut): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Statut $statut): bool
    {
        return $user->isAdmin();
    }
}
