<?php

namespace App\Repository;

use App\Models\Product;
use App\Models\Theme;

class ThemeRepository implements ThemeRepositoryInterface
{
    public function all()
    {
         return Theme::ofShop(AuthId())->paginate(10);
    }
    public function store(array $themeData, int $shopId): void
    {
        Theme::updateOrCreate(
            ['theme_id' => $themeData['id']],
            [
                'shop_id' => $shopId,
                'name' => $themeData['name'],
                'role' => $themeData['role'],
                'has_file' => false,
            ]
        );
    }
}
