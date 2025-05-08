<?php

namespace App\Repository;

interface ThemeRepositoryInterface
{
    public function store(array $themeData, int $shopId): void;
}
