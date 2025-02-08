<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $shop_id
 * @property string $shopify_id
 * @property string $topic
 * @property string $data
 * @property int $is_executed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $shop
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook query()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereIsExecuted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereShopifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereUserId($value)
 * @mixin \Eloquent
 */
class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'shopify_id',
        'topic',
        'data',
        'is_executed',
    ];

    public function shop()
    {
        return $this->belongsTo(User::class, 'shop_id', 'id');
    }
}
