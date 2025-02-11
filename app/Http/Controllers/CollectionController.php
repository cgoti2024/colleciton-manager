<?php

namespace App\Http\Controllers;

use App\Http\Resources\CollectionResource;
use App\Repository\CollectionRepositoryInterface;
use Illuminate\Http\Request;

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

    public function createCollection(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required',
            'description' => 'nullable',
            'products'    => 'required | array',
        ]);

        $shop = \Auth::user();
        $response = $this->collectionRepo->createCollection($validated, $shop);

        if ($response) return $this->sendSuccess('Collection created successfully!');
        else return $this->sendError('Something went wrong');
    }
}
