<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            $products = Product::with(['category', 'images'])
                ->paginate(4);
        } else {
            $products = Product::with(['category', 'images'])
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

        // Handle single image (backward compatibility)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        // Handle multiple images
        if ($request->hasFile('images')) {
            $primaryIndex = $request->input('primary_image_index', 0);
            
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                
                $product->images()->create([
                    'image_path' => $imagePath,
                    'order' => $index,
                    'is_primary' => $index == $primaryIndex,
                ]);
            }

            // If no single image was uploaded but multiple images were, set the first as main
            if (!$request->hasFile('image') && $product->images()->count() > 0) {
                $firstImage = $product->images()->orderBy('order')->first();
                $product->update(['image' => $firstImage->image_path]);
            }
        } elseif ($request->hasFile('image')) {
            // If only single image uploaded, create it as primary in product_images
            $product->images()->create([
                'image_path' => $data['image'],
                'order' => 0,
                'is_primary' => true,
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'images']);
        
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
        $product->load(['category', 'images']);
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

        // Handle single image (backward compatibility)
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        // Handle multiple images
        if ($request->hasFile('images')) {
            $primaryIndex = (int) $request->input('primary_image_index', 0);
            $existingImagesCount = $product->images()->count();
            
            // First, update all existing images to not be primary
            $product->images()->update(['is_primary' => false]);
            
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                
                $isPrimary = ($existingImagesCount + $index) == $primaryIndex;
                
                $product->images()->create([
                    'image_path' => $imagePath,
                    'order' => $existingImagesCount + $index,
                    'is_primary' => $isPrimary,
                ]);
                
                // Update product main image if this is primary
                if ($isPrimary) {
                    $product->update(['image' => $imagePath]);
                }
            }
        }

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
        $products = Product::with(['category', 'images'])
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->where('status', Product::STATUS_ACTIVE)
            ->paginate(5);

        return view('product.search', compact('products'));
    }

    /**
     * Delete a product image.
     */
    public function deleteImage(ProductImage $productImage)
    {
        // Check if user has permission
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($productImage->image_path)) {
            Storage::disk('public')->delete($productImage->image_path);
        }

        $product = $productImage->product;
        $productImage->delete();

        // If this was the primary image, set a new primary
        if ($product->images()->count() > 0) {
            $newPrimary = $product->images()->first();
            $newPrimary->update(['is_primary' => true]);
            $product->update(['image' => $newPrimary->image_path]);
        } else {
            // No images left, clear the main image field
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->update(['image' => null]);
        }

        return redirect()->back()->with('success', 'Image deleted successfully!');
    }
}
