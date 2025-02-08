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
 * @property string $shopify_order_id
 * @property string $order_number
 * @property string $financial_status
 * @property float $total_price
 * @property string|null $gateway
 * @property string|null $country
 * @property string|null $country_code
 * @property string|null $currency
 * @property string|null $currency_symbol
 * @property string|null $tags
 * @property string|null $shopify_customer_id
 * @property string|null $customer_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order ofShop($shopId)
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFinancialStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShopifyCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShopifyOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'shop_id',
        'shopify_order_id',
        'order_number',
        'total_price',
        'currency',
        'gateway',
        'customer_id',
        'customer_name',
        'customer_details',
        'shipping_address',
        'tags',
        'financial_status',
        'fulfillment_status'
    ];

    protected $casts = [
        'customers_details' => "array",
        'shipping_address' => "array",
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


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
