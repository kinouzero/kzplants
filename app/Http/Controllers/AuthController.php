<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller {

  /**
   * Login
   */
  public function login(Request $request) {
    if (auth()->attempt($request->only('email', 'password'))) return redirect()->intended('/');

    return redirect()->back()->withInput()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
  }

  /**
   * Logout
   */
  public function logout() {
    auth()->logout();

    return redirect('/');
  }
}
