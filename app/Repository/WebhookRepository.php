<?php

namespace App\Repository;

use Log;
use Exception;
use App\Models\Webhook;

/**
 * Class WebhookRepository
 * @package App\Repository
 */
class WebhookRepository implements WebhookRepositoryInterface
{

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;


    public function __construct(ProductRepositoryInterface $productRepo,) {
        $this->productRepo = $productRepo;
    }
    /**
     * @param Webhook $webhook
     * @return bool
     */
    public function productWebhook($webhook)
    {
        $payloadData = json_decode($webhook->data, 1);
        $shopId = $webhook->shop_id;

        $shop = $webhook->shop;
        if (empty($shop)) {
            return true;
        }

        try {
            $product  = $this->productRepo->store($$payloadData, $shopId);

        } catch (Exception $exception) {
            Log::error($exception->getMessage() . ' while ExecuteProductWebhookJob, Product Id:' . @$webhook->shopify_id);
        }

        return true;
    }
}
