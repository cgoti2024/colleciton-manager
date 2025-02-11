<?php

namespace App\Repository;

use App\Models\Webhook;
interface ProductRepositoryInterface
{
    public function all();

    public function store($payloadData, $shopId);

    public function filteredProducts($shopId, $query, $type);

    public function destroy($payloadData, $shopId);
}
