<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function index(Request $request)
    {
        if ($request->search) {
            $shopId = \Auth::id();
            $query = $request->search;
            $type = $request->type;
            $products = $this->productRepo->filteredProducts($shopId, $query, $type);
        } else {
            $products = $this->productRepo->all();
        }

        return ProductResource::collection($products);
    }
}
