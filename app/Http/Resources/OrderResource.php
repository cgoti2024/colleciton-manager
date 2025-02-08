<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'shopify_order_id' => $this->shopify_order_id,
            'order_number' => $this->order_number,
            'total_price' => $this->total_price,
            'currency' => $this->currency,
            'gateway' => $this->gateway,
            'customer_name' => $this->customer_name,
            'customer_details' => $this->customer_details,
            'shipping_address' => $this->shipping_address,
            'tags' => $this->tags,
            'financial_status' => $this->financial_status,
            'fulfillment_status' => $this->fulfillment_status?? "Unfulfilled",
            'order_items' => $this->items ? $this->items->count() : 0,
            'order_date' => $this->created_at
        ];
    }
}
