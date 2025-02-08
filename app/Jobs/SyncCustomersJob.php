<?php

namespace App\Jobs;

use App;
use App\Models\User;
use App\Repository\CustomerRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncCustomersJob implements ShouldQueue
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
        /** @var CustomerRepositoryInterface $customerRepo */
        $customerRepo = App::make(CustomerRepositoryInterface::class);
        try {
            $nextPage = null;
            do {
                info('Syncing customers for...', [$this->shop->name]);
                $response = $this->getShopifyCustomers($this->shop, $nextPage);
                if (isset($response['body']['customers'])) {
                    $customers = $response['body']['customers'];

                    foreach ($customers as $customer) {

                        $customer = $customer->toArray();
                        $customerRepo->store($customer, $this->shop->id);
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
    protected function getShopifyCustomers($user, $nextPage = null)
    {
        info('getShopifyCustomers called...');
        $params = [];
        $params['limit'] = 250;
        if ($nextPage !== null) {
            $params['page_info'] = $nextPage;
        }
        $response = $user->api()->rest('GET', '/admin/customers.json', $params);
        if ($response['status'] === 429) {
            info('Many Request and wait');
            sleep(1);
            $response = $this->getShopifyCustomers($user, $nextPage);
        }

        return $response;
    }
}
