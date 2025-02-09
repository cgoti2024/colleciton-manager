<?php

namespace App\Http\Controllers;

use App\Http\Resources\CollectionResource;
use App\Repository\CollectionRepositoryInterface;

class CollectionController extends Controller
{
    protected $collectionRepo;

    public function __construct(CollectionRepositoryInterface $collectionRepo)
    {
        $this->collectionRepo = $collectionRepo;
    }

    public function index()
    {
        $products = $this->collectionRepo->all();

        return CollectionResource::collection($products);
    }
}
