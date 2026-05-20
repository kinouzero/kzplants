<?php

namespace App\Policies;

use App\Models\Strain;
use App\Models\User;

class StrainPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Strain $strain): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Strain $strain): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Strain $strain): bool
    {
        return $user->isAdmin();
    }
}
