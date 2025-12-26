<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemRequest;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function increment(CartItem $cartItem)
    {
        $product = $cartItem->product;

        if ($product->stock < 1) {
            return back()->with('error', 'No more stock available.');
        }

        $cartItem->increment('quantity');
        $product->decrement('stock');

        return back()->with('success', 'Quantity increased!');
    }

    public function decrement(CartItem $cartItem)
    {
        if ($cartItem->quantity <= 1) {
            return back()->with('error', 'Quantity cannot be less than 1.');
        }

        $cartItem->decrement('quantity');
        $cartItem->product->increment('stock');
        return back()->with('success', 'Quantity decreased!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        $cartItem->product->increment('stock', $cartItem->quantity);

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart');
    }
}
