<?php

namespace App\Repository;

use App\Models\Customer;

/**
 * Class CustomerRepository
 * @package App\Repository
 */

class CustomerRepository implements CustomerRepositoryInterface
{

    public function all($input)
    {
        $customers  = Customer::ofShop(AuthId())->paginate();

        return $customers;
    }
   
     /**
     * @param $payloadData
     * @param $shopId
     * @return Customer
     */
    public function store($payloadData, $shopId)
    {
        $customer = Customer::updateOrCreate([
            'shopify_customer_id' => $payloadData['id'],
            'shop_id' => $shopId,
        ], [
            'first_name' => $payloadData['first_name'],
            'last_name' => $payloadData['last_name'],
            'email' => $payloadData['email'],
            'phone' => $payloadData['phone'],
            'orders_count' => $payloadData['orders_count'],
            'default_address' => json_encode($payloadData['default_address']),
        ]);

        return $customer;
    }
}
