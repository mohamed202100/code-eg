<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()) {
            $orders = Order::where('user_id', Auth::id())
                ->orderByRaw("
        CASE
            WHEN status = 'pending' THEN 1
            ELSE 2
        END
    ")
                ->latest()
                ->paginate(10);

            return view('order.index', compact('orders'));
        } else {
            return redirect()->route('login');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $cart = Auth::user()->cart()->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('carts.index')->with('error', 'Your cart is empty.');
        }

        $total = 0;

        foreach ($cart->cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->back()->with('error', "Requested quantity for {$item->product->title} exceeds stock.");
            }
            $total += $item->price * $item->quantity;
        }

        return view('order.create', compact('cart', 'total'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20|min:11',
            'address' => 'required|string|max:500|min:10',
        ]);

        $cart = Auth::check() ? Auth::user()->cart()->with('cartItems.product')->first() : null;

        if (!$cart || $cart->cartItems->count() === 0) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $order = Order::create([
            'user_id'     => Auth::id(),
            'full_name'        => $request->name,
            'mobile'       => $request->phone,
            'address'     => $request->address,
            'status'      => 'pending',
            'total_price' => $request->total,
        ]);

        foreach ($cart->cartItems as $item) {
            $order->orderItems()->create([
                'product_id' => $item->product->id,
                'quantity'   => $item->quantity,
                'price'      => $item->price,
                'size'       => $item->size,
                'color'      => $item->color,
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        if ($item->product->stock == 0) {
            $item->product->status = '0';
            $item->product->save();
        }


        $cart->cartItems()->delete();

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order placed successfully!');
    }


    public function show(Order $order)
    {
        $order->load('orderItems.product');

        return view('order.show', compact('order'));
    }
}
