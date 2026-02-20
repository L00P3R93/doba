<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthenticatedUsers
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect based on user role
            if ($user->hasRole('Admin')) {
                return redirect('/admin');
            } elseif ($user->hasRole('Artist')) {
                return redirect('/artist');
            }

            // Members are redirected to home
            return redirect('/');
        }

        return $next($request);
    }
}
