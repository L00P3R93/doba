<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('subscriptions', SubscriptionController::class);
Route::apiResource('user-subscriptions', UserSubscriptionController::class);
Route::apiResource('payments', PaymentController::class);

Route::post('/stk/callback', \App\Http\Controllers\Api\StkCallbackController::class)->name('stk.callback');
