<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index(): JsonResponse
    {
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

        return response()->json([
            'success' => true,
            'data' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|min:11',
            'address' => 'required|string|max:500|min:10',
            'direct_order' => 'nullable|boolean',
            'product_id' => 'required_if:direct_order,true|exists:products,id',
            'quantity' => 'required_if:direct_order,true|integer|min:1',
            'size' => 'required_if:direct_order,true|string|max:50',
            'color' => 'required_if:direct_order,true|string|max:50',
        ]);

        $isDirectOrder = $request->has('direct_order') && $request->direct_order == true;

        if ($isDirectOrder) {
            // Direct order from product
            $product = \App\Models\Product::findOrFail($validated['product_id']);
            
            if ($validated['quantity'] > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested quantity exceeds available stock.',
                ], 400);
            }

            $total = $product->price * $validated['quantity'];

            $order = Order::create([
                'user_id' => Auth::id(),
                'full_name' => $validated['name'],
                'mobile' => $validated['phone'],
                'address' => $validated['address'],
                'status' => 'pending',
                'total_price' => $total,
            ]);

            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
                'size' => $validated['size'],
                'color' => $validated['color'],
            ]);

            $product->decrement('stock', $validated['quantity']);
            
            if ($product->stock == 0) {
                $product->status = 0;
                $product->save();
            }
        } else {
            // Order from cart
            $cart = Auth::user()->cart()->with('cartItems.product')->first();

            if (!$cart || $cart->cartItems->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.',
                ], 400);
            }

            // Validate stock for all items
            foreach ($cart->cartItems as $item) {
                if ($item->quantity > $item->product->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Requested quantity for {$item->product->title} exceeds stock.",
                    ], 400);
                }
            }

            $total = $cart->cartItems->sum(fn($item) => $item->price * $item->quantity);

            $order = Order::create([
                'user_id' => Auth::id(),
                'full_name' => $validated['name'],
                'mobile' => $validated['phone'],
                'address' => $validated['address'],
                'status' => 'pending',
                'total_price' => $total,
            ]);

            $lastProduct = null;
            foreach ($cart->cartItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'size' => $item->size,
                    'color' => $item->color,
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

        // Notify admins
        $admins = \Illuminate\Support\Facades\Cache::remember('admin_users', 3600, function () {
            return User::where('role', '=', 'admin')->get();
        });
        foreach ($admins as $admin) {
            $admin->notify(new NewOrderNotification($order));
        }

        $order->load('orderItems.product');

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'data' => new OrderResource($order),
        ], 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $order->load('orderItems.product', 'user');

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order),
        ]);
    }
}
