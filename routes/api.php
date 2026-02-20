<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerSubscriptionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('customer-subscriptions', CustomerSubscriptionController::class);
Route::apiResource('subscriptions', SubscriptionController::class);
Route::apiResource('payments', PaymentController::class);

