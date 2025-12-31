<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            $products = Product::orderBy('created_at', 'desc')->paginate(10);
        } else {
            $products = Product::where('status', '=', '1')->latest()->paginate(10);
        }
        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        if ($product->status != '1') {
            return response()->json(['message' => 'Product not available'], 403);
        }

        return new ProductResource($product);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return new ProductResource($product);
    }

    public function toggleStatus(Product $product)
    {
        $product->status = !$product->status;
        $product->save();

        return response()->json([
            'success' => true,
            'status'  => $product->status
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['success' => true]);
    }
}
