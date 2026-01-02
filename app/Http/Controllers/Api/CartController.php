<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartItemResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the authenticated user's cart.
     */
    public function index(): JsonResponse
    {
        $cart = Auth::user()->cart()->with('cartItems.product')->first();

        if (!$cart) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Cart is empty',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Add item to cart.
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
        ]);

        $cart = Auth::user()->cart ?? Cart::create(['user_id' => Auth::id()]);

        $cartItem = $cart->cartItems()
            ->where('product_id', $product->id)
            ->where('size', $validated['size'])
            ->where('color', $validated['color'])
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            
            if ($newQuantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested quantity exceeds available stock.',
                ], 400);
            }

            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $product->price,
            ]);
        } else {
            if ($validated['quantity'] > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested quantity exceeds available stock.',
                ], 400);
            }

            $cartItem = $cart->cartItems()->create([
                'product_id' => $product->id,
                'size' => $validated['size'],
                'color' => $validated['color'],
                'quantity' => $validated['quantity'],
                'price' => $product->price,
            ]);
        }

        $cartItem->load('product');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'data' => new CartItemResource($cartItem),
        ], 201);
    }

    /**
     * Remove the specified cart.
     */
    public function destroy(): JsonResponse
    {
        $cart = Auth::user()->cart;

        if (!$cart) {
            return response()->json([
                'success' => true,
                'message' => 'Cart is already empty',
            ]);
        }

        $cart->cartItems()->delete();
        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
        ]);
    }
}
