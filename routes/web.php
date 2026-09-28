<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'splash')->name('splash');

Route::view('/marketplace', 'public.home')
    ->name('marketplace.home');
