<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'product_id',
        'shopify_option_id',
        'name',
        'values',
        'position'
    ];

    protected $casts = [
      'values' => 'array'
    ];
}
