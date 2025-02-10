<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title' => $this->title,
            'shopify_product_id' => $this->shopify_product_id,
            'description' => $this->description,
            'handle' => $this->handle,
            'image_url' => $this->image_url,
            'images' => $this->images,
            'tags' => $this->tags,
            'supplier' => $this->supplier,
            'product_type' => $this->productType,
            'status' => $this->status,
            'first_variant' => $this->variants ? $this->variants[0] : null
        ];
    }
}
