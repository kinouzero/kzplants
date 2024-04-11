<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ThemeController extends Controller {

  public function toggle() {
    session(['theme' => User::getTheme() === 'light' ? 'dark' : 'light']);
    return session('theme');
  }
}
