<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $shop_id
 * @property int $product_id
 * @property int $shopify_option_id
 * @property string $name
 * @property array $values
 * @property int|null $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereShopifyOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereValues($value)
 * @mixin \Eloquent
 */
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
