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
 * @property int $order_id
 * @property string $shopify_line_item_id
 * @property string $name
 * @property string $product_id
 * @property string $product_title
 * @property string $variant_id
 * @property string|null $variant_title
 * @property int $quantity
 * @property float $price
 * @property float $subtotal
 * @property float $discount_amount
 * @property string|null $vendor
 * @property string|null $sku
 * @property string|null $currency
 * @property string|null $currency_symbol
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User $shop
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem ofShop($shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereProductTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereShopifyLineItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereVariantTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereVendor($value)
 * @mixin \Eloquent
 */
class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'order_id',
        'shopify_item_id',
        'shopify_product_id',
        'shopify_variant_id',
        'name',
        'quantity',
        'price',
        'discount_amount',
        'vendor',
        'sku',
    ];

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


     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(User::class, 'shop_id', 'id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'shopify_product_id');
    }

}
