<?php

use App\Http\Controllers\UnifiedLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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

Route::post('/subscribe', \App\Http\Controllers\StkSubscribeController::class)->name('subscribe.pay');

// Dashboard route with role-based redirect
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'redirect.authenticated'])
    ->name('dashboard');

require __DIR__.'/settings.php';
