<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\GoogleLinkController;
use App\Http\Controllers\StkSubscribeController;
use App\Http\Controllers\UnifiedLoginController;
use App\Livewire\Advertise;
use App\Livewire\Home;
use App\Livewire\Pricing;
use App\Livewire\Privacy;
use App\Livewire\Terms;
use App\Livewire\TvShow;
use App\Livewire\WatchMovie;
use App\Livewire\WatchTv;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

// Watch routes — movie and TV streaming pages
Route::get('/watch/movie/{tmdbId}', WatchMovie::class)->name('watch.movie');
Route::get('/watch/tv/{tmdbId}/{season}/{episode}', WatchTv::class)->name('watch.tv');
Route::get('/tv/{tmdbId}', TvShow::class)->name('tv.show');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Email verification routes
Route::middleware('auth')->group(function () {
    Route::get('/auth/google/link', [GoogleLinkController::class, 'redirect'])->name('auth.google.link');
    Route::get('/auth/google/link/callback', [GoogleLinkController::class, 'callback'])->name('auth.google.link.callback');
});

// Logout route (Fortify handles login)
Route::post('/logout', [UnifiedLoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Unified pricing page (listener + creator plans behind a fader toggle)
Route::get('/pricing', Pricing::class)->name('pricing');

// Legacy URLs redirect to the merged page, defaulting to the matching side
Route::redirect('/premium', '/pricing?mode=listener', 301)->name('premium');
Route::redirect('/subscribe', '/pricing?mode=creator', 301)->name('subscribe');

// Adverts Page
Route::get('/advertise', Advertise::class)->name('advertise');

Route::post('/subscribe', StkSubscribeController::class)->name('subscribe.pay');

// Dashboard route with role-based redirect
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'redirect.authenticated'])
    ->name('dashboard');

Route::get('/privacy', Privacy::class)->name('privacy');
Route::get('/terms', Terms::class)->name('terms');

require __DIR__.'/settings.php';
