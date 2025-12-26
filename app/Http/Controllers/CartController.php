<?php

namespace App\Http\Controllers;

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
        $cart = Auth::user()->cart()->with('cartItems.product')->first();
        return view('cart.index', compact('cart'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartItemRequest  $request, Product $product)
    {
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

        $product->decrement('stock', $request->quantity);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
