<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = User::getUserLanguage(auth()->user());
        if ($locale) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
