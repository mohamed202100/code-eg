<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'cart_items' => CartItemResource::collection($this->whenLoaded('cartItems')),
            'total' => $this->whenLoaded('cartItems', function () {
                return $this->cartItems->sum(fn($item) => $item->price * $item->quantity);
            }),
            'items_count' => $this->whenLoaded('cartItems', fn() => $this->cartItems->count()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
