<?php

namespace App\Repository;

interface CollectionRepositoryInterface
{
    public function all();

    public function store($payloadData, $shopId);

    public function destroy($payloadData, $shopId);
}
