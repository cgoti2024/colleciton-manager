<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['verify.shopify']], static function () {
    Route::get('/themes', [ThemeController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/themes/sync', [ThemeController::class, 'sync']);
});
