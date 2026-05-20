<?php

namespace App\Http\Controllers;

use App\Models\User;

class ThemeController extends Controller
{
    public function toggle()
    {
        session(['theme' => User::getTheme() === 'light' ? 'dark' : 'light']);

        return session('theme');
    }
}
