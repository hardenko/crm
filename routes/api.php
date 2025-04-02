<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComponentController;
use App\Http\Controllers\Api\ProductController;

Route::get('components', [ComponentController::class, 'getComponentList']);
Route::get('products', [ProductController::class, 'getProductList']);
Route::get('users', [UserController::class, 'getUserList']);
Route::get('clients', [ClientController::class, 'getClientList']);

Route::middleware(['permission:add component'])->group(function () {
    Route::post('components', [ComponentController::class, 'addComponent']);
});

