<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->hasVerifiedEmail()) {
            // If accessing Filament panels, redirect to verification notice
            $currentPanel = Filament::getCurrentPanel();
            if (($currentPanel && $request->is($currentPanel->getPath() . '*')) ||
                str_starts_with($request->path(), 'admin') ||
                str_starts_with($request->path(), 'artist') ||
                str_starts_with($request->path(), 'studio') ||
                str_starts_with($request->path(), 'record') ||
                str_starts_with($request->path(), 'event')) {

                return Redirect::route('verification.notice')
                    ->with('message', 'You must verify your email address before accessing any panels.');
            }

            // For API routes, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your email address is not verified.',
                    'verification_required' => true,
                ], 403);
            }

            // For web routes, redirect to verification notice
            return Redirect::route('verification.notice');
        }

        return $next($request);
    }
}
