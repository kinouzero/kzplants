<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (auth()->attempt($request->only('email', 'password'))) return redirect()->intended('/');

        return redirect()->back()->withInput()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }
}
