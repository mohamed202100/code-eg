<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusChanged;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest()->paginate(10);

        return view('admin.order.index', compact('orders'));
    }



    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load([
            'orderItems.product',
            'user'
        ]);

        return view('admin.order.show', compact('order'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled,refunded',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        $order->user->notify(new OrderStatusChanged($order));


        return back()->with('success', 'Order status updated successfully');
    }

    public function invoice($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        $pdf = Pdf::loadView('admin.order.invoice', compact('order'));

        return $pdf->download('invoice-order-' . $order->id . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
