<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    /**
     * Increment cart item quantity.
     */
    public function increment(CartItem $cartItem): JsonResponse
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $product = $cartItem->product;

        if ($product->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => 'No more stock available.',
            ], 400);
        }

        $cartItem->increment('quantity');
        $cartItem->load('product');

        return response()->json([
            'success' => true,
            'message' => 'Quantity increased',
            'data' => new CartItemResource($cartItem),
        ]);
    }

    /**
     * Decrement cart item quantity.
     */
    public function decrement(CartItem $cartItem): JsonResponse
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        if ($cartItem->quantity <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Quantity cannot be less than 1.',
            ], 400);
        }

        $cartItem->decrement('quantity');
        $cartItem->load('product');

        return response()->json([
            'success' => true,
            'message' => 'Quantity decreased',
            'data' => new CartItemResource($cartItem),
        ]);
    }

    /**
     * Remove the specified cart item.
     */
    public function destroy(CartItem $cartItem): JsonResponse
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
        ]);
    }
}
