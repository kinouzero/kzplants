<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('SEEDER_USER_MAIL', '');
        if ($email === '') {
            return;
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => (string) env('SEEDER_USER_NAME', 'User 1'),
                'password' => Hash::make((string) env('SEEDER_USER_PWD', 'user')),
            ]
        );

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['description' => 'Administrator']);
        if (! $user->roles()->where('role_id', $adminRole->id)->exists()) {
            $user->roles()->attach($adminRole->id);
        }
    }
}
