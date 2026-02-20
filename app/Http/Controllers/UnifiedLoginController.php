<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UnifiedLoginController extends Controller
{
    public function create()
    {
        return view('pages.auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Check if user has appropriate role for panel access
        if (! $user->hasRole('Admin') && ! $user->hasRole('Artist')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Members cannot access admin panels.',
            ]);
        }

        // Redirect based on user role
        if ($user->hasRole('Admin')) {
            return redirect()->intended('/admin');
        } elseif ($user->hasRole('Artist')) {
            return redirect()->intended('/artist');
        }

        return redirect('/');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
