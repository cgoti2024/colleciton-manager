<?php

namespace App\Jobs;

use App;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $shop;

    /**
     * Create a new job instance.
     */
    public function __construct($shop)
    {
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        saveSetting($this->shop, 'PRODUCT_SYNC_PROCESS', 'start');
        try {
            $hasNextPage = true;
            $cursor = null;
            $mainIndex = 0;
            while ($hasNextPage) {
                $fetchProductsQuery = <<<GRAPHQL
                    {
                       products(first: 250, sortKey: TITLE {$this->buildCursorPart($cursor)}) {
                        edges {
                            cursor
                            node {
                                id
                                title
                                handle
                                bodyHtml
                                vendor
                                productType
                                tags
                                status
                                featuredImage {
                                    url
                                }
                                metafields(namespace: "custom", first: 25) {
                                    edges {
                                        node {
                                            key
                                            value
                                        }
                                    }
                                }
                                variants(first: 10) {
                                    edges {
                                        node {
                                            id
                                            title
                                            position
                                            price
                                            sku
                                            compareAtPrice
                                            sellableOnlineQuantity
                                            selectedOptions {
                                               name
                                               value
                                            }
                                        }
                                    }
                                }
                         }
                     }
                        pageInfo {
                            hasNextPage
                        }
                        }
                    }
                GRAPHQL;

                $response = $this->shop->api()->graph($fetchProductsQuery);
                if ($response && !$response['errors'] && isset($response['body']['data']['products']['edges'])) {
                    $data = $response['body']['data']['products'];
                    $products = $data['edges'];
                    $hasNextPage = $data['pageInfo']['hasNextPage'];
                    $cursor = $products[count($products) - 1]['cursor'] ?? null;
                    $this->insertProductInDB($response, $this->shop->id);
                    sleep(1);
                    saveSetting($this->shop, 'SYNCED_PRODUCT_COUNT', ($mainIndex+1)*250);
                } else {
                    info('there is error for retrieving products for mainindex => '.$mainIndex);
                }

                $mainIndex++;
            }
            saveSetting($this->shop, 'PRODUCT_SYNC_PROCESS', 'end');
        } catch (\Exception $exception) {
            info('Error while syncing products: ' . $exception->getMessage());
            saveSetting($this->shop, 'PRODUCT_SYNC_PROCESS', 'failed');
            saveSetting($this->shop, 'PRODUCT_SYNC_ERROR', ['exception' => $exception->getMessage(), 'index' => $mainIndex]);
        }
    }

    private function buildCursorPart($cursor)
    {
        if (!empty($cursor)) {
            return ", after: \"{$cursor}\"";
        }

        return '';
    }

    private function insertProductInDB($response, $shopId) {
        foreach ($response['body']['data']['products']['edges'] as $productEdge) {
            $productNode = $productEdge['node'];

            $shopifyProductId = str_replace('gid://shopify/Product/', '', @$productNode['id']);
            $description = @$productNode['bodyHtml'] ?? null;
            if ($description && strlen($description) > 650000) {
                $description = substr($description, 0, 650000);
            }

            $metafields = [];
            if (isset($productNode['metafields']['edges'])) {
                foreach ($productNode['metafields']['edges'] as $metafieldEdge) {
                    $metafieldNode = $metafieldEdge['node'];
                    $metafields[$metafieldNode['key']] = $metafieldNode['value'];
                }
            }

            $product = Product::updateOrCreate([
                'shopify_product_id' => $shopifyProductId,
                'shop_id'            => $shopId,
            ], [
                'title'        => @$productNode['title'],
                'handle'       => @$productNode['handle'],
                'description'  => @$description,
                'supplier'     => @$productNode['vendor'],
                'status'       => @$productNode['status'],
                'tags'         => @$productNode['tags'],
                'product_type' => @$productNode['productType'],
                'image_url' => @$productNode['featuredImage']['url'] ?? null,
                'metafields' => $metafields
            ]);


            foreach ($productNode['variants']['edges'] as $variantEdge) {
                $variantNode = $variantEdge['node'];
                ProductVariant::updateOrCreate([
                    'shop_id' => $shopId,
                    'shopify_variant_id' => str_replace('gid://shopify/ProductVariant/', '', $variantNode['id']),
                ], [
                    'product_id' => $product->id,
                    'shopify_variant_id' => str_replace('gid://shopify/ProductVariant/', '', $variantNode['id']),
                    'title' => $variantNode['title'],
                    'position' => $variantNode['position'],
                    'price' => $variantNode['price'],
                    'sku' => $variantNode['sku'],
                    'inventory_quantity' => $variantNode['sellableOnlineQuantity'],
                    'option1' => @$variantNode['selectedOptions'][0]['value'] ?? null,
                    'option2' => @$variantNode['selectedOptions'][1]['value'] ?? null,
                    'option3' => @$variantNode['selectedOptions'][2]['value'] ?? null,
                ]);
            }
        }

    }
}
