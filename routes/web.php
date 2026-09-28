<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'splash')->name('splash');

Route::view('/marketplace', 'public.marketplace-placeholder')
    ->name('marketplace.home');
