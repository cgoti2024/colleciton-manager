<?php

namespace App\Repository;

use App\Models\Collection;

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
            'status'          => 'Active',
            'published_at'    => @$payloadData['published_at'],
            'products_count'  => @$payloadData['products_count'] ?? 0,
            'collection_type' => @$payloadData['collection_type'] ?? 'Manual',
            'image_url'       => @$payloadData['image']['src'],
        ]);

        return true;
    }

    public function destroy($payloadData, $shopId)
    {
        $collectionId = @$payloadData['id'];
        if ($collectionId) {
            Collection::where('shop_id', $shopId)->where('shopify_collection_id', $collectionId)->delete();
        }
    }
}
