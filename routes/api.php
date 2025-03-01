<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComponentController;
use App\Http\Controllers\Api\ProductController;

Route::get('/components', [ComponentController::class, 'index']);
Route::get('/components/{id}', [ComponentController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
