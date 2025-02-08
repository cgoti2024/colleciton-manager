<?php

namespace App\Jobs;

use App;
use App\Models\User;
use App\Repository\OrderRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncOrdersJob implements ShouldQueue
{
    use Queueable;

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
        /** @var OrderRepositoryInterface $orderRepo */
        $orderRepo = App::make(OrderRepositoryInterface::class);

        try {
            $nextPage = null;
            do {
                info('Syncing orders for...', [$this->shop->name]);
                $response = $this->getShopifyOrders($this->shop, $nextPage);
                if (isset($response['body']['orders'])) {
                    $orders = $response['body']['orders'];

                    foreach ($orders as $order) {
                        $order = $order->toArray();

                        $orderRepo->store($order, $this->shop->id);
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
            info('Error while syncing Orders: ' . $exception->getMessage());
        }
    }

    /**
     *  Get Shopify products.
     *
     * @param  mixed  $user
     * @param  mixed|null  $nextPage
     */
    protected function getShopifyOrders($user, $nextPage = null)
    {
        info('getShopifyOrders called...');
        $params = [];
        $params['limit'] = 250;
        if ($nextPage !== null) {
            $params['page_info'] = $nextPage;
        }
        $response = $user->api()->rest('GET', '/admin/orders.json', $params);
        if ($response['status'] === 429) {
            info('Many Request and wait');
            sleep(1);
            $response = $this->getShopifyOrders($user, $nextPage);
        }

        return $response;
    }
}
