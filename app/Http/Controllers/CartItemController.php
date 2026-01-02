<?php

namespace App\Http\Controllers;

use App\Helpers\SessionCartHelper;
use App\Http\Requests\CartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function increment($cartItem)
    {
        if (Auth::check()) {
            // Authenticated user
            $cartItem = CartItem::findOrFail($cartItem);
            $product = $cartItem->product;

            if ($product->stock < 1) {
                return back()->with('error', 'No more stock available.');
            }

            $cartItem->increment('quantity');

            return back()->with('success', 'Quantity increased!');
        } else {
            // Guest user - $cartItem is the key
            $cart = SessionCartHelper::getCart();
            
            if (!isset($cart[$cartItem])) {
                return back()->with('error', 'Item not found in cart.');
            }

            $productId = $cart[$cartItem]['product_id'];
            $product = Product::findOrFail($productId);

            if ($product->stock < 1) {
                return back()->with('error', 'No more stock available.');
            }

            SessionCartHelper::incrementItem($cartItem);

            return back()->with('success', 'Quantity increased!');
        }
    }

    public function decrement($cartItem)
    {
        if (Auth::check()) {
            // Authenticated user
            $cartItem = CartItem::findOrFail($cartItem);

            if ($cartItem->quantity <= 1) {
                return back()->with('error', 'Quantity cannot be less than 1.');
            }

            $cartItem->decrement('quantity');
            return back()->with('success', 'Quantity decreased!');
        } else {
            // Guest user - $cartItem is the key
            $cart = SessionCartHelper::getCart();
            
            if (!isset($cart[$cartItem])) {
                return back()->with('error', 'Item not found in cart.');
            }

            if ($cart[$cartItem]['quantity'] <= 1) {
                return back()->with('error', 'Quantity cannot be less than 1.');
            }

            SessionCartHelper::decrementItem($cartItem);
            return back()->with('success', 'Quantity decreased!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cartItem)
    {
        if (Auth::check()) {
            // Authenticated user
            $cartItem = CartItem::findOrFail($cartItem);
            $cartItem->delete();

            return back()->with('success', 'Item removed from cart');
        } else {
            // Guest user - $cartItem is the key
            if (SessionCartHelper::removeItem($cartItem)) {
                return back()->with('success', 'Item removed from cart');
            }

            return back()->with('error', 'Item not found in cart.');
        }
    }
}
