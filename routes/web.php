<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('welcome');
})->middleware(['verify.shopify'])->name('home');

Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*')->middleware(['verify.shopify']);
