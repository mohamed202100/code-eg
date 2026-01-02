@props(['product'])

<div class="col-md-4 col-lg-3 mb-4">
    <div class="card h-100 shadow-sm border-0 product-card">
        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
            <div class="product-image-wrapper">
                @php
                    // Get main image: primary image, or first image, or fallback to old image field
                    $mainImage = $product->images->where('is_primary', true)->first()?->image_path 
                                 ?? $product->images->first()?->image_path 
                                 ?? $product->image;
                @endphp
                @if($mainImage)
                    <img src="{{ asset('storage/' . $mainImage) }}" 
                         class="card-img-top product-image" 
                         alt="{{ $product->title }}">
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        <i class="ti-image" style="font-size: 3rem;"></i>
                    </div>
                @endif
            </div>
        </a>
        <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-2 text-truncate" title="{{ $product->title }}">
                <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                    {{ $product->title }}
                </a>
            </h5>
            <p class="card-text mb-2">
                <strong class="text-primary fs-5">${{ number_format($product->price, 2) }}</strong>
            </p>
            @if($product->stock > 0)
                <p class="text-muted small mb-2">
                    <i class="ti-check-box"></i> In Stock ({{ $product->stock }})
                </p>
            @else
                <p class="text-danger small mb-2">
                    <i class="ti-close"></i> Out of Stock
                </p>
            @endif
            <div class="mt-auto">
                <div class="d-flex flex-column gap-2">
                    @can('edit products')
                        <a href="{{ route('products.edit', $product->id) }}"
                            class="btn btn-sm btn-info w-100">
                            <i class="ti-pencil-alt"></i> Edit
                        </a>
                    @endcan

                    @can('delete products')
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger w-100"
                                onclick="return confirm('Are you sure you want to delete this product?')">
                                <i class="ti-trash"></i> Delete
                            </button>
                        </form>
                    @endcan

                    @can('view products')
                        <a href="{{ route('products.show', $product->id) }}"
                            class="btn btn-sm btn-success w-100">
                            <i class="ti-shopping-cart"></i> View Details
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    
    .product-image-wrapper {
        height: 250px;
        background-color: #f8f9fa;
        overflow: hidden;
        position: relative;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .card-title a:hover {
        color: #007bff !important;
    }
</style>

