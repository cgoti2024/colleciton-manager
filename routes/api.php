<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['verify.shopify']], static function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
