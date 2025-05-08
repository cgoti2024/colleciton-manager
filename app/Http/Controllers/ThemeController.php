<?php

namespace App\Http\Controllers;

use App\Http\Resources\ThemeResource;
use App\Jobs\SyncThemesJob;
use App\Repository\ThemeRepositoryInterface;
use Illuminate\Http\Request;

class ThemeController extends Controller
{

    protected $themeRepo;

    public function __construct(ThemeRepositoryInterface $themeRepo)
    {
        $this->themeRepo = $themeRepo;
    }

    public function index()
    {
        $products = $this->themeRepo->all();

        return ThemeResource::collection($products);
    }
    public function sync(Request $request)
    {
        $shop = \Auth::user();
        SyncThemesJob::dispatch($shop);

        return response()->json(['message' => 'Theme sync initiated.']);
    }
}
