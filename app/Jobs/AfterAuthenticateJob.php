<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AfterAuthenticateJob implements ShouldQueue
{
    use Queueable;

    public $shopDomain;

    /**
     * Create a new job instance.
     */
    public function __construct($shopDomain)
    {
        $this->shopDomain = $shopDomain['name'];
        info('AfterAuthenticateJob --- : ' . $this->shopDomain);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var User $shop */
        $shop = User::whereName($this->shopDomain)->firstOrFail();

        if(empty($shop)) {
            info('Shop not found --- : ' . $this->shopDomain);
            return;
        }

        SyncCollectionJob::dispatch($shop);
        $this->saveProductCount($shop);
    }

    public function saveProductCount($shop) {
        $response = $shop->api()->rest('GET', '/admin/products/count.json');
        info('Product count'.$response['body']['count']);
        if ($response['status'] == 200 && isset($response['body']['count'])) {
            saveSetting($shop, 'TOTAL_PRODUCT_COUNTS', $response['body']['count']);
        }
    }
}
