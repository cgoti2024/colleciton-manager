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

    public function index()
    {
        $products = $this->productRepo->all();

        return ProductResource::collection($products);
    }
}
