<?php

namespace App\Repository;

use App\Models\Webhook;
interface ProductRepositoryInterface
{
    public function all();

    public function store($payloadData, $shopId);

    public function filteredProducts($shopId, $query, $type, $from);

    public function destroy($payloadData, $shopId);
}
