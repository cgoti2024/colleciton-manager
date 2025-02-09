<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::count();
        $orders = Collection::count();

        return $this->sendResponse([
           'products' => $products,
           'collections' => $orders,
        ]);
    }
}
