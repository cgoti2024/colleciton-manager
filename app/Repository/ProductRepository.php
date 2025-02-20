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
        return Product::ofShop(AuthId())->paginate(10);
    }

    public function filteredProducts($shopId, $query, $type , $from = 'productController')
    {
        $keywords = explode(',', $query);
        $products = Product::where('shop_id', $shopId);

        if ($type === 'tags') {
            $products->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhereJsonContains('tags', trim($keyword));
                }
            });
        } elseif ($type === 'title') {
            $products->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('title', 'like', "%".trim($keyword)."%");
                }
            });
        } elseif ($type === 'sku') {
            $products->whereHas('variants', function ($q) use ($keywords) {
                foreach ($keywords as $i => $keyword) {
                    if ($i == 0) {
                        $q->where('sku', 'like', "%" . trim($keyword) . "%");
                    } else {
                        $q->orWhere('sku', 'like', "%" . trim($keyword) . "%");
                    }
                }
            });
        } elseif ($type === 'product_type') {
            $products->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('product_type', 'like', "%".trim($keyword)."%");
                }
            });
        } elseif ($type === 'all') {
            $products->where(function ($q) use ($keywords) {
                foreach ($keywords as $i => $keyword) {
                    $q->orWhere('title', 'like', "%".trim($keyword)."%")
                        ->orWhereJsonContains('tags', trim($keyword))
                        ->orWhere('handle', 'like', "%".trim($keyword)."%")
                        ->orWhere('description', 'like', "%".trim($keyword)."%")
                        ->orWhere('supplier', 'like', "%".trim($keyword)."%")
                        ->orWhere('product_type', 'like', "%".trim($keyword)."%")
                        ->orWhere('metafields', 'like', "%".trim($keyword)."%")
                        ->orWhereHas('variants', function ($q) use ($keyword, $i) {
                            if ($i == 0) {
                                $q->where('sku', 'like', "%" . trim($keyword) . "%");
                            } else {
                                $q->orWhere('sku', 'like', "%" . trim($keyword) . "%");
                            }
                        });
                }
            });
        }

        if ($from === 'collection') {
            return $products->pluck('shopify_product_id');
        }
        return $products->paginate(10);
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
            'tags'         => explode(', ',$payloadData['tags']),
            'product_type' => $payloadData['product_type'],
            'images'       => json_encode($payloadData['images']),
            'image_url'    => @$payloadData['image']['src'],
        ]);

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
