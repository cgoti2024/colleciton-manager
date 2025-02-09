<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int $shopify_collection_id
 * @property int $shop_id
 * @property string $title
 * @property string $handle
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $products_count
 * @property string $collection_type
 * @property string|null $image_url
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCollectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereProductsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereShopifyCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection withoutTrashed()
 * @mixin \Eloquent
 */
class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shopify_collection_id',
        'shop_id',
        'title',
        'handle',
        'status',
        'published_at',
        'products_count',
        'collection_type',
        'image_url',
    ];

    /**
     * Cast attributes to native types.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime'
    ];
}
