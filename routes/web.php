<?php

use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web'])->group(function () {
    Route::get('/switch-locale/{lang}', [LocaleController::class, 'switch'])
        ->name('switch-locale');
});
