<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            $products = Product::with('category')
                ->paginate(4);
        } else {
            $products = Product::with('category')
                ->where('status', Product::STATUS_ACTIVE)
                ->orderBy('created_at', 'desc')
                ->paginate(4);
        }
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \Illuminate\Support\Facades\Cache::remember('categories_list', 3600, function () {
            return Category::all();
        });
        return view('product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');
        
        if ($product->status) {
            return view('product.show', compact('product'));
        } else
            abort(403, 'Product not available');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('category');
        $categories = \Illuminate\Support\Facades\Cache::remember('categories_list', 3600, function () {
            return Category::all();
        });

        return view('product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Category deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $products = Product::with('category')
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->where('status', Product::STATUS_ACTIVE)
            ->paginate(5);

        return view('product.search', compact('products'));
    }
}
