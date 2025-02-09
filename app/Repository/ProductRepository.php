<?php

namespace App\Repository;

use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductVariant;
use function Illuminate\Process\options;

/**
 * Class ProductRepository
 * @package App\Repository
 */
class ProductRepository implements ProductRepositoryInterface
{

    public function all()
    {
        $products = Product::ofShop(AuthId())->paginate(10);

        return $products;
    }

    /**
     * @param $payloadData
     * @param $shopId
     * @return bool
     */
    public function store($payloadData, $shopId)
    {
        $product = Product::updateOrCreate([
            'shopify_product_id' => $payloadData['id'],
            'shop_id'            => $shopId,
        ], [
            'title'        => $payloadData['title'],
            'handle'       => $payloadData['handle'],
            'description'  => $payloadData['body_html'],
            'supplier'     => $payloadData['vendor'],
            'status'       => $payloadData['status'],
            'tags'         => $payloadData['tags'],
            'product_type' => $payloadData['product_type'],
            'images'       => json_encode($payloadData['images']),
            'image_url'    => @$payloadData['image']['src'],
        ]);

        if (isset($payloadData['options']) && count($payloadData['options'])) {
            foreach ($payloadData['options'] as $index => $option) {
                $product->productOptions()->updateOrCreate([
                    'product_id'        => $product['id'],
                    'shopify_option_id' => $option['id'],
                ], [
                    'shop_id'           => $shopId,
                    'name'              => $option['name'],
                    'values'            => $option['values'],
                    'position'          => $index + 1,
                ]);
            }
        }

        foreach (@$payloadData['variants'] as $variant) {
            ProductVariant::updateOrCreate([
                'shopify_variant_id' => $variant['id'],
                'shop_id'            => $shopId,
            ], [
                'product_id'         => $product['id'],
                'title'              => $variant['title'],
                'position'           => $variant['position'],
                'price'              => $variant['price'],
                'inventory_quantity' => $variant['inventory_quantity'],
                'option1'            => $variant['option1'],
                'option2'            => $variant['option2'],
                'option3'            => $variant['option3'],
            ]);
        }

        return true;
    }

    public function destroy($payloadData, $shopId) {
        $productId = @$payloadData['id'];
        if ($productId) {
            Product::where('shop_id', $shopId)->where('shopify_product_id', $productId)->delete();
        }
    }
}
