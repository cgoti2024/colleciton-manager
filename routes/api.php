<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['verify.shopify']], static function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/collections', [CollectionController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/sync-product-status', [SettingController::class, 'syncProducts']);
    Route::get('/sync-product', [SettingController::class, 'startProductSync']);
    Route::post('/create-collections', [CollectionController::class, 'createCollection']);
});
