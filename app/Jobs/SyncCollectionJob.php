<?php

namespace App\Jobs;

use App;
use App\Models\User;
use App\Repository\CollectionRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncCollectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $shop;

    /**
     * Create a new job instance.
     */
    public function __construct($shop)
    {
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var ProductRepositoryInterface $collectionRepo */
        $collectionRepo = App::make(CollectionRepositoryInterface::class);

        try {
            $nextPage = null;
            do {
                $response = $this->getShopifyCollections($this->shop, $nextPage);
                if (isset($response['body']['custom_collections'])) {
                    $collections = $response['body']['custom_collections'];
                    foreach ($collections as $collect) {
                        $collectionRepo->store($collect, $this->shop->id);
                    }
                }

                if ($response['link'] !== null) {
                    $nextPage = null;
                    if (@$response['link']->container['next']) {
                        $nextPage = @explode(';', @$response['link']->container['next'])[0];
                    }
                }
            } while ($nextPage !== null);
        } catch (\Exception $exception) {
            info('Error while syncing products: ' . $exception->getMessage());
        }
    }

    /**
     *  Get Shopify products.
     *
     * @param  mixed  $user
     * @param  mixed|null  $nextPage
     */
    protected function getShopifyCollections($user, $nextPage = null)
    {
        $params = [];
        $params['limit'] = 250;
        if ($nextPage !== null) {
            $params['page_info'] = $nextPage;
        }
        $response = $user->api()->rest('GET', '/admin/custom_collections.json', $params);
        if ($response['status'] === 429) {
            info('Many Request and wait');
            sleep(1);
            $response = $this->getShopifyCollections($user, $nextPage);
        }

        return $response;
    }
}
