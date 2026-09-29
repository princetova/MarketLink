<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredCustomerController;
use App\Http\Controllers\Auth\RegisteredFarmerController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'splash')->name('splash');

Route::view('/marketplace', 'public.home')
    ->name('marketplace.home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
    Route::get('/register/customer', [RegisteredCustomerController::class, 'create'])
        ->name('register.customer');
    Route::post('/register/customer', [RegisteredCustomerController::class, 'store'])
        ->name('register.customer.store');
    Route::get('/register/farmer', [RegisteredFarmerController::class, 'create'])
        ->name('register.farmer');
    Route::post('/register/farmer', [RegisteredFarmerController::class, 'store'])
        ->name('register.farmer.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
