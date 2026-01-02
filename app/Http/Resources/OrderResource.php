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
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'status' => $this->status,
            'total_price' => $this->total_price,
            'full_name' => $this->full_name,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'items_count' => $this->whenLoaded('orderItems', fn() => $this->orderItems->count()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
