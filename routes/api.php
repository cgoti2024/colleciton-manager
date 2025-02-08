<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['verify.shopify']], static function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
