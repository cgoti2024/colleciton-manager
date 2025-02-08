<?php

namespace App\Repository;

use App\Models\Customer;
use App\Models\Order;
interface CustomerRepositoryInterface
{

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function all($input);


    /**
     * @param $payloadData
     * @param $shopId
     * 
     * @return Customer
     */

    public function store($payloadData, $shopId);
}
