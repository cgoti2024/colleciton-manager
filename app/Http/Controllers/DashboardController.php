<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Theme;

class DashboardController extends Controller
{
    public function index()
    {
        $themes = Theme::ofShop(AuthId())->count();

        return $this->sendResponse([
           'themes' => $themes
        ]);
    }
}
