<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\Webhook;

interface OrderRepositoryInterface
{
    public function all($input);

    public function store($payloadData, $shopId);
}
