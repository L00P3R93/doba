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

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('message', 'Please verify your email address before accessing your account.');
        }

        // Redirect based on user role
        return match (true) {
            $user->hasRole('Guest') => redirect('/subscribe'),
            $user->hasRole('Admin') => redirect('/admin'),
            $user->hasRole('Artist') => redirect('/artist'),
            $user->hasRole('Event') => redirect('/event'),
            $user->hasRole('Studio') => redirect('/studio'),
            $user->hasRole('Record') => redirect('/record'),
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
