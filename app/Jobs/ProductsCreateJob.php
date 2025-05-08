<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Webhook;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductsCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     /**
     * Shop's myshopify domain
     *
     * @var string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param string   $shopDomain The shop's myshopify domain.
     * @param $data       The webhook data (JSON decoded).
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        set_time_limit(0);
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $shop = User::where('name',$this->shopDomain)->first();

        if(empty($shop)) {
            info('Shop Not Found in ProductCreateJob: '.$this->shopDomain);
            return response(true, 200);
        }

        $product = json_encode($this->data);
        $shopifyId = json_decode($product)->id;

        $entity = Webhook::updateOrCreate(
            ['shopify_id' => $shopifyId, 'topic' => 'products/create', 'shop_id' => $shop->id],
            ['shopify_id' => $shopifyId, 'topic' => 'products/create', 'shop_id' => $shop->id, 'data' => $product, 'is_executed' => 0]
        );

        ExecuteProductsJob::dispatch($entity->id);

        return response(true, 200);
    }
}
