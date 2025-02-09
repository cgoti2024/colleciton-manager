<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('welcome');
})->middleware(['verify.shopify'])->name('home');

Route::get('/webhooks', function () {

    try {
        $shopDomain = 'local-app-2.myshopify.com';
        $shop = \App\Models\User::where('name', $shopDomain)->first();
        $response = $shop->api()->rest('GET', '/admin/webhooks.json');
        if ($response['status'] === 200) {
            return response()->json([
                'success' => true,
                'webhooks' => $response['body']['webhooks']
            ], 200);
        } else {
            return ['error' => 'Failed to fetch webhooks', 'details' => $response];
        }
    } catch (\Exception $e) {
        return ['error' => 'Exception occurred', 'message' => $e->getMessage()];
    }
})->middleware(['verify.shopify'])->name('webhooks');

Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*')->middleware(['verify.shopify']);
