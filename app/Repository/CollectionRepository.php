<?php

namespace App\Repository;

use App\Models\Collection;
use App;
/**
 * Class ProductRepository
 * @package App\Repository
 */
class CollectionRepository implements CollectionRepositoryInterface
{

    public function all()
    {
        return Collection::where('shop_id', AuthId())->paginate(10);
    }

    public function store($payloadData, $shopId)
    {
        Collection::updateOrCreate([
            'shopify_collection_id' => $payloadData['id'],
            'shop_id'               => $shopId,
        ], [
            'title'           => @$payloadData['title'],
            'handle'          => @$payloadData['handle'],
            'status'          => @$payloadData['published_at'] ? 'Active' : 'Draft',
            'published_at'    => @$payloadData['published_at'],
            'products_count'  => @$payloadData['products_count'] ?? 0,
            'collection_type' => @$payloadData['collection_type'] ?? 'Manual',
            'image_url'       => @$payloadData['image']['src'],
        ]);

        return true;
    }

    public function createCollection($payloadData, $shop) {
        if ($payloadData['allSelected'] == 1) {
            $productRepo = App::make(ProductRepositoryInterface::class);
            $productIds = $productRepo->filteredProducts($shop->id, $payloadData['query'], $payloadData['type'] , 'collection');
        } else {
            $productIds = $payloadData['products'];
        }
        try {
            $params = [
                'custom_collection' => [
                    'title' => $payloadData['title'],
                    'body_html' => $payloadData['description'],
                    'collects' => array_map(function ($productId) {
                        return ['product_id' => $productId];
                    }, $productIds),
                ],
            ];

            $response = $shop->api()->rest('POST', '/admin/custom_collections.json', $params);

            if (isset($response['body']['custom_collection'])) {
                $this->store($response['body']['custom_collection'], $shop->id);
                return $response['body']['custom_collection'];
            } else {
                info('Error creating custom collection', ['response' => $response]);
                return null;
            }
        } catch (\Exception $e) {
            info('Exception creating custom collection', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function destroy($payloadData, $shopId)
    {
        $collectionId = @$payloadData['id'];
        if ($collectionId) {
            Collection::where('shop_id', $shopId)->where('shopify_collection_id', $collectionId)->delete();
        }
    }
}
