<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int $shop_id
 * @property int $product_id
 * @property int $shopify_variant_id
 * @property string|null $title
 * @property int $position
 * @property float $price
 * @property int $compare_at_price
 * @property int|null $inventory_quantity
 * @property string|null $image_url
 * @property string|null $option1
 * @property string|null $option2
 * @property string|null $option3
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $shop
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant ofShop($shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereCompareAtPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereInventoryQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereOption1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereOption2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereOption3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereShopifyVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'shop_id',
        'product_id',
        'shopify_variant_id',
        'title',
        'sku',
        'position',
        'price',
        'inventory_quantity',
        'option1',
        'option2',
        'option3',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(User::class, 'shop_id', 'id');
    }

     /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $shopId
     *
     * @return mixed
     */
    public function scopeOfShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }
}
