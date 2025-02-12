<?php

namespace App\Http\Controllers;

use App\Jobs\SyncProductsJob;

class SettingController extends Controller
{
    public function syncProducts()
    {
        $shop = \Auth::user();
        $productSyncStatus = getSetting($shop, 'PRODUCT_SYNC_PROCESS');
        $syncedProducts = getSetting($shop, 'SYNCED_PRODUCT_COUNT');
        $totalProducts = getSetting($shop, 'TOTAL_PRODUCT_COUNTS');

        return $this->sendResponse([
            'productSyncStatus' => @$productSyncStatus->value,
            'syncedProducts'    => @$syncedProducts->value,
            'totalProducts'     => @$totalProducts->value
        ]);
    }

    public function startProductSync() {
        $shop = \Auth::user();
        $status = getSetting($shop, 'PRODUCT_SYNC_PROCESS');
        if (!$status || $status->value === 'failed') {
            SyncProductsJob::dispatch($shop);
        }

        return $this->sendSuccess('Product sync process started!');
    }
}
