<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\GoogleLinkController;
use App\Http\Controllers\UnifiedLoginController;
use App\Livewire\Home;
use App\Livewire\Privacy;
use App\Livewire\Terms;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Email verification routes
Route::middleware('auth')->group(function () {
    Route::get('/auth/google/link', [GoogleLinkController::class, 'redirect'])->name('auth.google.link');
    Route::get('/auth/google/link/callback', [GoogleLinkController::class, 'callback'])->name('auth.google.link.callback');
});

// Unified login routes - single entry point for all users
Route::get('/login', [UnifiedLoginController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [UnifiedLoginController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');

Route::post('/logout', [UnifiedLoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Subscribe page for Guest users
Route::get('/subscribe', function () {
    return view('subscribe');
})->middleware('auth')->name('subscribe');

// Premium Page
Route::view('/premium', 'premium')->name('premium');

// Adverts Page
Route::view('/advertise', 'advertise')->name('advertise');

Route::post('/subscribe', \App\Http\Controllers\StkSubscribeController::class)->name('subscribe.pay');

// Dashboard route with role-based redirect
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'redirect.authenticated'])
    ->name('dashboard');

Route::get('/privacy', Privacy::class)->name('privacy');
Route::get('/terms', Terms::class)->name('terms');

require __DIR__.'/settings.php';
