<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web'])->group(function () {
    Route::get('/switch-locale/{lang}', function ($lang) {
        if (in_array($lang, ['en', 'uk'])) {
            Session::put('locale', $lang);
        }
        return redirect()->back();
    })->name('switch-locale');
});
