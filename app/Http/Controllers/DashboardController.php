<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::count();
        $collections = Collection::count();
        $shop = \Auth::user();
        $productSyncStatus = getSetting($shop, 'PRODUCT_SYNC_PROCESS');
        $syncedProducts = getSetting($shop, 'SYNCED_PRODUCT_COUNT');
        $totalProducts = getSetting($shop, 'TOTAL_PRODUCT_COUNTS');

        return $this->sendResponse([
           'products' => $products,
           'collections' => $collections,
           'productSyncStatus' => @$productSyncStatus->value,
           'syncedProducts'    => @$syncedProducts->value,
           'totalProducts'     => @$totalProducts->value
        ]);
    }
}
