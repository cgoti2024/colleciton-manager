<?php

namespace App\Console\Commands;

use App\Jobs\SyncCollectionJob;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\title;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::find(2);
        $response = $user->api()->rest('GET', '/admin/products/count.json');
        dd($response['body']['count']);
        $this->getProducts($user);
//        $this->createCollection($user);
    }

    public function getProducts($user) {
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

            $response = $user->api()->graph($fetchProductsQuery);
            if ($response && !$response['errors'] && isset($response['body']['data']['products']['edges'])) {
                $data = $response['body']['data']['products'];
                $products = $data['edges'];
                $hasNextPage = $data['pageInfo']['hasNextPage'];
                $cursor = $products[count($products) - 1]['cursor'] ?? null;
                $this->insertProductInDB($response, $user->id);
                sleep(1);
            } else {
                info('there is error for retrieving products for mainindex => '.$mainIndex);
            }

            $mainIndex++;
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

    protected function createCollection($user) {
        $names = ['test15', 'test2', 'test3', 'test4', 'test11', 'test21', 'test31', 'test41', 'test12', 'test22', 'test32', 'test42'];
        foreach ($names as $n) {
                $input = [
                    'custom_collection' => [
                        'title' => $n,
                        'collects' => [
                            ['product_id' => 8873196650740],
                            ['product_id' => 8873196585204],
                            ['product_id' => 8873196749044],
                        ],
                    ],
                ];

                $response = $user->api()->rest('POST', '/admin/custom_collections.json', $input);
        }
    }
}
