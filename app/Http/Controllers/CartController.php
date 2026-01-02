<?php

namespace App\Http\Controllers;

use App\Helpers\SessionCartHelper;
use App\Http\Requests\CartItemRequest;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;

use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            $cart = Auth::user()->cart()->with('cartItems.product')->first();
            $isGuest = false;
        } else {
            // Guest cart from session
            $cartItems = SessionCartHelper::getCartItemsWithProducts();
            $cart = (object) [
                'cartItems' => collect($cartItems)->map(function ($item) {
                    return (object) [
                        'key' => $item['key'],
                        'product' => $item['product'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'size' => $item['size'],
                        'color' => $item['color'],
                    ];
                })
            ];
            $isGuest = true;
        }

        if (!$cart || ($isGuest ? $cart->cartItems->isEmpty() : $cart->cartItems->isEmpty())) {
            return redirect(route('products.index'))->with('error', 'Your cart is empty.');
        }

        return view('cart.index', compact('cart', 'isGuest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartItemRequest  $request, Product $product)
    {
        if (Auth::check()) {
            // Authenticated user cart
            $cart = Auth::user()->cart ?? Cart::create(['user_id' => Auth::id()]);

            $cartItem = $cart->cartItems()->where('product_id', $product->id)
                ->where('size', $request->size)
                ->where('color', $request->color)
                ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;

                if ($newQuantity > $product->stock + $cartItem->quantity) {
                    return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
                }

                $cartItem->update([
                    'quantity' => $newQuantity,
                    'price'    => $product->price,
                ]);
            } else {
                if ($request->quantity > $product->stock) {
                    return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
                }

                $cartItem = $cart->cartItems()->create([
                    'product_id' => $product->id,
                    'size'       => $request->size,
                    'color'      => $request->color,
                    'quantity'   => $request->quantity,
                    'price'      => $product->price,
                ]);
            }
        } else {
            // Guest cart in session
            if ($request->quantity > $product->stock) {
                return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
            }

            // Check if item already exists in session cart
            $cart = SessionCartHelper::getCart();
            $key = SessionCartHelper::generateItemKey($product->id, $request->size, $request->color);

            if (isset($cart[$key])) {
                $newQuantity = $cart[$key]['quantity'] + $request->quantity;
                if ($newQuantity > $product->stock) {
                    return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
                }
                SessionCartHelper::updateItemQuantity($key, $newQuantity);
            } else {
                SessionCartHelper::addItem(
                    $product->id,
                    $request->quantity,
                    $product->price,
                    $request->size,
                    $request->color
                );
            }
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
}
