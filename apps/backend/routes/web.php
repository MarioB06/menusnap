<?php

use App\Http\Controllers\GuestMenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/menu/{restaurantSlug}/{tableUuid}', [GuestMenuController::class, 'show'])
    ->name('guest.menu');
