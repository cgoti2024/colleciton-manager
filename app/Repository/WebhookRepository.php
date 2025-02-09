<?php

namespace App\Repository;

use Log;
use Exception;
use App\Models\Order;
use App\Models\Webhook;

/**
 * Class WebhookRepository
 * @package App\Repository
 */
class WebhookRepository implements WebhookRepositoryInterface
{

    private $collectionRepo;

    private $productRepo;

    public function __construct(
        ProductRepositoryInterface $productRepo,
        CollectionRepositoryInterface $collectionRepo

    ) {
        $this->productRepo = $productRepo;
        $this->collectionRepo = $collectionRepo;
    }

    /**
     * @param Webhook $webhook
     *
     * @return bool|mixed
     */
    public function collectionWebhook($webhook)
    {
        try {
            $payloadData = json_decode($webhook->data, 1);
            $shopId = $webhook->shop_id;

            if ($webhook->topic === 'collections/delete') {
                $this->collectionRepo->destroy($payloadData, $shopId);
            } else {
                $this->collectionRepo->store($payloadData, $shopId);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage() . ' while ExecuteOrderWebhookJob, Order Id:' . @$webhook->shopify_id);
        }

        return true;
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
            if ($webhook->topic === 'products/delete') {
                $this->productRepo->destroy($payloadData, $shopId);
            } else {
                $this->productRepo->store($payloadData, $shopId);
            }

        } catch (Exception $exception) {
            Log::error($exception->getMessage() . ' while ExecuteProductWebhookJob, Product Id:' . @$webhook->shopify_id);
        }

        return true;
    }
}
