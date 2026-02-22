<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Redirect based on user role
                if ($user->hasRole('Admin')) {
                    return redirect('/admin');
                } elseif ($user->hasRole('Artist')) {
                    return redirect('/artist');
                } elseif ($user->hasRole('Guest')) {
                    return redirect('/guest');
                } elseif ($user->hasRole('Studio')) {
                    return redirect('/studio');
                } elseif ($user->hasRole('Record')) {
                    return redirect('/record');
                } elseif ($user->hasRole('Event')) {
                    return redirect('/event');
                }

                // Members are redirected to home
                return redirect('/');
            }
        }

        return $next($request);
    }
}
