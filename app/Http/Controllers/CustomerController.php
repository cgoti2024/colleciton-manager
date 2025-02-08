<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Repository\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function index(Request $request)
    {
        $customers = $this->customerRepo->all($request->all());

        return CustomerResource::collection($customers);
    }
}
