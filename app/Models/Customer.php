<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'shopify_customer_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'orders_count',
        'default_address',
    ];

    protected $casts = [
        'default_address' => "array",
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
    public function shop()
    {
        return $this->belongsTo(User::class, 'shop_id', 'id');
    }
}
