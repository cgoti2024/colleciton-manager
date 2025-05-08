<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'theme_id',
        'name',
        'role',
        'has_file'
    ];

    protected $casts = [
        'has_file' => 'boolean'
    ];

    public function scopeOfShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }
}
