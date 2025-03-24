<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComponentController;
use App\Http\Controllers\Api\ProductController;

Route::get('component-list', [ComponentController::class, 'getComponentList']);
Route::get('product-list', [ProductController::class, 'getProductList']);
Route::get('user-list', [UserController::class, 'getUserList']);
Route::get('client-list', [ClientController::class, 'getClientList']);

Route::middleware(['permission:add component'])->group(function () {
    Route::post('component', [ComponentController::class, 'addComponent']);
});

