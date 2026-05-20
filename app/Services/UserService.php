<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $data): User
    {
        $user = new User;
        $user->fill(Arr::only($data, ['name', 'email']));

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $this->syncRelations($user, $data);

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->fill(Arr::only($data, ['name', 'email']));

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $this->syncRelations($user, $data);

        return $user;
    }

    private function syncRelations(User $user, array $data): void
    {
        if (Arr::exists($data, 'roles')) {
            $roles = Arr::get($data, 'roles', []);
            $user->roles()->sync($roles);
        }

        $preferences = Arr::get($data, 'preferences', null);
        if ($preferences === null) {
            return;
        }
        $values = [];
        if ($preferences) {
            foreach ($preferences as $preferenceId => $value) {
                if ($value !== null && $value !== '') {
                    $values[] = [
                        'user_id' => $user->id,
                        'preference_id' => $preferenceId,
                        'value' => is_array($value) ? json_encode($value) : $value,
                    ];
                }
            }
        }

        if ($values) {
            $user->preferences()->sync($values);
        } else {
            $user->preferences()->sync([]);
        }
    }
}
