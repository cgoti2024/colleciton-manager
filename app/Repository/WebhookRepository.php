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

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;
    
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var CustomerRepositoryInterface 
     */
    private $customerRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        ProductRepositoryInterface $productRepo,
        CustomerRepositoryInterface $customerRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
        $this->customerRepo = $customerRepo;
    }

    /**
     * @param Webhook $webhook
     *
     * @return bool|mixed
     */
    public function orderWebhook(Webhook $webhook)
    {
        try {
            $payloadData = json_decode($webhook->data, 1);
            $shopId = $webhook->shop_id;

            $order = $this->orderRepo->store($payloadData, $shopId);
        
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
            $product  = $this->productRepo->store($$payloadData, $shopId);
           
        } catch (Exception $exception) {
            Log::error($exception->getMessage() . ' while ExecuteProductWebhookJob, Product Id:' . @$webhook->shopify_id);
        }

        return true;
    }

    public function customerWebhook($webhook)
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
            Log::error($exception->getMessage() . ' while ExecuteCustomerWebhookJob, Customer Id:' . @$webhook->shopify_id);
        }
    }
}
