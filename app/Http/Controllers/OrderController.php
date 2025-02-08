<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Repository\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index(Request $request)
    {
        $orders = $this->orderRepo->all($request->all());

        return OrderResource::collection($orders);
    }
}
