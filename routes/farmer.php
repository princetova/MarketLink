<?php

use App\Http\Controllers\Farmer\AccountStatusController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/status', AccountStatusController::class)
    ->middleware(['auth', 'role:'.User::ROLE_FARMER])
    ->name('status');
