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
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['category', 'images']);

        // Self-heal: If product has legacy image but no images relation, create one
        if ($product->image && $product->images->count() === 0) {
            $product->images()->create([
                'image_path' => $product->image,
                'order' => 0,
                'is_primary' => true
            ]);
            $product->load('images'); // Refresh
        }

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
        // Self-heal: Ensure legacy image is migrated before processing update
        // This handles cases where update is called directly or edit view didn't trigger migration
        if ($product->image && $product->images()->count() === 0) {
            $product->images()->create([
                'image_path' => $product->image,
                'order' => 0,
                'is_primary' => true
            ]);
        }

        $data = $request->validated();

        // Handle single image (backward compatibility - legacy field removal if present in request but ignored by new UI)
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');

            // Should also create a product_image for it if we are maintaining sync
            $product->images()->create([
                'image_path' => $data['image'],
                'order' => $product->images()->count(),
                'is_primary' => $product->images()->count() == 0
            ]);
        }

        $product->update($data);

        // Handle setting primary image from existing images
        $primaryImageId = $request->input('existing_primary_image');
        if ($primaryImageId) {
            // Reset all images to not primary
            $product->images()->update(['is_primary' => false]);
            // Set the selected image as primary
            $primaryImage = $product->images()->find($primaryImageId);
            if ($primaryImage) {
                $primaryImage->update(['is_primary' => true]);
                $product->update(['image' => $primaryImage->image_path]);
            }
        }

        // Handle multiple images (adding new ones)
        if ($request->hasFile('images')) {
            $primaryIndex = (int) $request->input('primary_image_index', -1); // -1 means no new image is primary
            $existingImagesCount = $product->images()->count();

            // If no existing primary was set and we have new images, the first new image becomes primary by default
            if (!$primaryImageId && $primaryIndex === -1 && count($request->file('images')) > 0) {
                $primaryIndex = $existingImagesCount; // First new image
            }

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
        } elseif (!$primaryImageId && $product->images()->count() > 0) {
            // If no primary image was set and we have existing images, make sure one is primary
            $primaryImage = $product->images()->where('is_primary', true)->first();
            if (!$primaryImage) {
                $firstImage = $product->images()->first();
                $firstImage->update(['is_primary' => true]);
                $product->update(['image' => $firstImage->image_path]);
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
