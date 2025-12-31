<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', '=', '1')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products)
        ]);
    }

    public function show(Product $product)
    {
        if ($product->status != '1') {
            return response()->json(['message' => 'Product not available'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product)
        ], 200);
    }
}
