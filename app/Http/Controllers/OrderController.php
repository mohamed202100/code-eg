<?php

namespace App\Http\Controllers;

use App\Helpers\SessionCartHelper;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\NewOrderNotification;
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
                ->with('orderItems.product')
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
        if (Auth::check()) {
            $cart = Auth::user()->cart()->with('cartItems.product')->first();
            $isGuest = false;

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
        } else {
            // Guest cart
            $cartItems = SessionCartHelper::getCartItemsWithProducts();
            $isGuest = true;

            if (empty($cartItems)) {
                return redirect()->route('carts.index')->with('error', 'Your cart is empty.');
            }

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

            $total = 0;
            foreach ($cart->cartItems as $item) {
                if ($item->quantity > $item->product->stock) {
                    return redirect()->back()->with('error', "Requested quantity for {$item->product->title} exceeds stock.");
                }
                $total += $item->price * $item->quantity;
            }
        }

        return view('order.create', compact('cart', 'total', 'isGuest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        // Check if this is a direct order (from Order Now button)
        $isDirectOrder = $request->has('direct_order') && $request->direct_order == '1';
        $directOrderData = null;
        
        if ($isDirectOrder) {
            $directOrderData = [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'size' => $request->size,
                'color' => $request->color,
            ];
        }

        if (Auth::check()) {
            // Authenticated user
            if ($isDirectOrder && $directOrderData) {
                // Direct order - create order from product data
                $product = \App\Models\Product::findOrFail($directOrderData['product_id']);
                
                if ($directOrderData['quantity'] > $product->stock) {
                    return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
                }

                $order = Order::create([
                    'user_id'     => Auth::id(),
                    'full_name'   => $request->name,
                    'mobile'      => $request->phone,
                    'address'     => $request->address,
                    'status'      => 'pending',
                    'total_price' => $request->total,
                ]);

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity'   => $directOrderData['quantity'],
                    'price'      => $product->price,
                    'size'       => $directOrderData['size'],
                    'color'      => $directOrderData['color'],
                ]);

                $product->decrement('stock', $directOrderData['quantity']);
                
                if ($product->stock == 0) {
                    $product->status = 0;
                    $product->save();
                }
            } else {
                // Regular cart order
                $cart = Auth::user()->cart()->with('cartItems.product')->first();

                if (!$cart || $cart->cartItems->count() === 0) {
                    return redirect()->back()->with('error', 'Your cart is empty.');
                }

                $order = Order::create([
                    'user_id'     => Auth::id(),
                    'full_name'   => $request->name,
                    'mobile'      => $request->phone,
                    'address'     => $request->address,
                    'status'      => 'pending',
                    'total_price' => $request->total,
                ]);

                $lastProduct = null;
                foreach ($cart->cartItems as $item) {
                    $order->orderItems()->create([
                        'product_id' => $item->product->id,
                        'quantity'   => $item->quantity,
                        'price'      => $item->price,
                        'size'       => $item->size,
                        'color'      => $item->color,
                    ]);

                    $item->product->decrement('stock', $item->quantity);
                    $lastProduct = $item->product;
                }

                if ($lastProduct && $lastProduct->stock == 0) {
                    $lastProduct->status = 0;
                    $lastProduct->save();
                }

                $cart->cartItems()->delete();
            }
        } else {
            // Guest user
            if ($isDirectOrder && $directOrderData) {
                // Direct order for guest
                $product = \App\Models\Product::findOrFail($directOrderData['product_id']);
                
                if ($directOrderData['quantity'] > $product->stock) {
                    return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
                }

                // Store guest name in session for potential registration
                SessionCartHelper::setGuestName($request->name);

                $order = Order::create([
                    'user_id'     => null,
                    'full_name'   => $request->name,
                    'mobile'      => $request->phone,
                    'address'     => $request->address,
                    'status'      => 'pending',
                    'total_price' => $request->total,
                ]);

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity'   => $directOrderData['quantity'],
                    'price'      => $product->price,
                    'size'       => $directOrderData['size'],
                    'color'      => $directOrderData['color'],
                ]);

                $product->decrement('stock', $directOrderData['quantity']);
                
                if ($product->stock == 0) {
                    $product->status = 0;
                    $product->save();
                }
            } else {
                // Regular cart order for guest
                $cartItems = SessionCartHelper::getCartItemsWithProducts();

                if (empty($cartItems)) {
                    return redirect()->back()->with('error', 'Your cart is empty.');
                }

                // Store guest name in session for potential registration
                SessionCartHelper::setGuestName($request->name);

                $order = Order::create([
                    'user_id'     => null,
                    'full_name'   => $request->name,
                    'mobile'      => $request->phone,
                    'address'     => $request->address,
                    'status'      => 'pending',
                    'total_price' => $request->total,
                ]);

                $lastProduct = null;
                foreach ($cartItems as $item) {
                    $order->orderItems()->create([
                        'product_id' => $item['product']->id,
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                        'size'       => $item['size'],
                        'color'      => $item['color'],
                    ]);

                    $item['product']->decrement('stock', $item['quantity']);
                    $lastProduct = $item['product'];
                }

                if ($lastProduct && $lastProduct->stock == 0) {
                    $lastProduct->status = 0;
                    $lastProduct->save();
                }

                // Clear guest cart
                SessionCartHelper::clear();
            }
        }

        // Notify admins (cache admin list for performance)
        $admins = \Illuminate\Support\Facades\Cache::remember('admin_users', 3600, function () {
            return User::where('role', '=', 'admin')->get();
        });
        foreach ($admins as $admin) {
            $admin->notify(new NewOrderNotification($order));
        }

        // Store order ID in session for guest users to view their order
        if (!Auth::check()) {
            session(['guest_order_id' => $order->id, 'guest_order_phone' => $request->phone]);
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order placed successfully!');
    }


    /**
     * Show direct order form for a single product
     */
    public function directOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'color' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);

        // Check stock
        if ($request->quantity > $product->stock) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
        }

        if ($product->status != \App\Models\Product::STATUS_ACTIVE) {
            return redirect()->back()->with('error', 'Product is not available.');
        }

        // Store direct order data in session
        session([
            'direct_order' => [
                'product_id' => $request->product_id,
                'size' => $request->size,
                'color' => $request->color,
                'quantity' => $request->quantity,
            ]
        ]);

        // Create a temporary cart structure for direct order
        $cartItem = (object) [
            'product' => $product,
            'quantity' => $request->quantity,
            'price' => $product->price,
            'size' => $request->size,
            'color' => $request->color,
        ];

        $cart = (object) [
            'cartItems' => collect([$cartItem])
        ];

        $total = $product->price * $request->quantity;
        $isGuest = !Auth::check();
        $isDirectOrder = true;

        return view('order.create', compact('cart', 'total', 'isGuest', 'isDirectOrder', 'product'));
    }

    public function show(Order $order)
    {
        // Allow viewing if:
        // 1. User is authenticated and owns the order
        // 2. User is admin
        // 3. User is guest and has the order ID in session with matching phone
        if (Auth::check()) {
            $this->authorize('view', $order);
        } else {
            // Guest user - check session
            $guestOrderId = session('guest_order_id');
            $guestOrderPhone = session('guest_order_phone');
            
            if ($guestOrderId != $order->id || $guestOrderPhone != $order->mobile) {
                abort(403, 'Unauthorized access to this order.');
            }
        }

        $order->load('orderItems.product');

        return view('order.show', compact('order'));
    }
}
