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

        // Redirect based on user role
        return match (true) {
            $user->hasRole('Guest') => redirect()->intended('/guest'),
            $user->hasRole('Admin') => redirect()->intended('/admin'),
            $user->hasRole('Artist') => redirect()->intended('/artist'),
            $user->hasRole('Event') => redirect()->intended('/event'),
            $user->hasRole('Studio') => redirect()->intended('/studio'),
            $user->hasRole('Record') => redirect()->intended('/record'),
            default => redirect('/'),
        };
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
