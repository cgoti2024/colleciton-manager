<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shopify_collection_id' => @$this->shopify_collection_id,
            'title' => $this->title,
            'handle' => $this->handle,
            'image_url' => $this->image_url,
            'status' => $this->status,
            'published_at' => @$this->published_at,
            'products_count' => @$this->products_count,
        ];
    }
}
