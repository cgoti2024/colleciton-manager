<?php namespace App\Jobs;

use App\Models\User;
use App\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;

class CollectionsDeleteJob implements ShouldQueue
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
            info('Shop Not Found in OrderCreateJob: '.$this->shopDomain);
            return response(true, 200);
        }

        $collection = json_encode($this->data);
        $shopifyId = json_decode($collection)->id;

        $entity = Webhook::updateOrCreate(
            ['shopify_id' => $shopifyId, 'topic' => 'collections/delete', 'shop_id' => $shop->id],
            ['shopify_id' => $shopifyId, 'topic' => 'collections/delete', 'shop_id' => $shop->id, 'data' => $collection, 'is_executed' => 0]
        );

        ExecuteCollectionsJob::dispatch($entity->id);

        return response(true, 200);
    }
}
