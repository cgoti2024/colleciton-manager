<?php

namespace App\Http\Controllers;

use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::count();

        return $this->sendResponse([
           'products' => $products
        ]);
    }
}
