<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\OrderItem;
use Exception;

/**
 * Class OrderRepository
 * @package App\Repository
 */

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @param array $input
     * @return Order[]\Illuminate\Database\Eloquent\Collection
     */
    public function all($input)
    {
        $orders = Order::ofShop(AuthId())->paginate(10);

        return $orders;
    }

    /**
     * @param array $payloadData
     * @param int $shopId
     * @return boolean
     */
    public function store($payloadData, $shopId)
    {
        try {
            $order = Order::updateOrCreate([
                'shop_id' => $shopId,
                'shopify_order_id' => $payloadData['id'],
            ], [
                'order_number' => $payloadData['order_number'],
                'total_price' => $payloadData['current_total_price'],
                'gateway' => implode(',', $payloadData['payment_gateway_names']),
                'customer_name' => @$payloadData['customer']['first_name'] . ' ' . @$payloadData['customer']['last_name'],
                'customer_details' => json_encode($payloadData['customer']),
                'customer_id' => @$payloadData['customer']['id'],
                'shipping_address' => json_encode($payloadData['shipping_address']),
                'currency' => $payloadData['currency'],
                'tags' => $payloadData['tags'],
                'financial_status' => $payloadData['financial_status'],
                'fulfillment_status' => $payloadData['fulfillment_status'],
            ]);

            if(empty($order)) {
                info("Order not updted..!!");
                return false;
            }

            $lineItems = $payloadData['line_items'];

            foreach ($lineItems as $lineItem) {

                $item = OrderItem::updateOrCreate([
                    'shop_id' => $shopId,
                    'order_id' => $order->id,
                    'shopify_item_id' => $lineItem['id'],
                ], [
                    'name' => $lineItem['name'],
                    'shopify_product_id' => $lineItem['product_id'],
                    'shopify_variant_id' => $lineItem['variant_id'],
                    'quantity' => $lineItem['quantity'],
                    'price' => $lineItem['price'],
                    'discount_amount' => $lineItem['total_discount'],
                    'vendor' => $lineItem['vendor'],
                    'sku' => $lineItem['sku'],
                ]);
            }
        } catch (Exception $e) {
            info("Error while updated order details: " . $e->getMessage());
            info("Shop id: " . $shopId. " Order id: " . $payloadData['id']);
        }

        return true;
    }
}
