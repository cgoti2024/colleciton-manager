<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::count();
        $orders = Order::count();
        $customers = Customer::count();

        return $this->sendResponse([
           'products' => $products,
           'orders' => $orders,
           'customers' => $customers
        ]);
    }
}
