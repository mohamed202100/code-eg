<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="Webmin - Bootstrap 4 & Angular 5 Admin Dashboard Template" />
    <meta name="author" content="potenzaglobalsolutions.com" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    @include('layouts.head')
</head>

<body>

    <div class="wrapper">

        <!--=================================
 preloader -->

        <div id="pre-loader">
            <img src="assets/images/pre-loader/loader-01.svg" alt="">
        </div>

        <!--=================================
 preloader -->

        @include('layouts.main-header')

        @include('layouts.main-sidebar')

        <!--=================================
 Main content -->
        <!-- main-content -->
        <div class="content-wrapper">

            <!-- Page Title -->
            <div class="page-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="mb-0">Product Details</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                            <li class="breadcrumb-item"><a href="/" class="default-color">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('products.index') }}"
                                    class="default-color">Products</a></li>
                            <li class="breadcrumb-item active">{{ $product->title }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <div class="row">
                                <!-- Left Column: Images -->
                                <div class="col-lg-5 mb-4 mb-lg-0">
                                    <div class="product-gallery">
                                        <!-- Main Image -->
                                        <div class="product-main-image mb-3 border rounded">
                                            <img id="main-product-image"
                                                src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()?->image_path ?? ($product->images->first()?->image_path ?? $product->image))) }}"
                                                class="img-fluid w-100" style="max-height: 500px; object-fit: contain;"
                                                alt="{{ $product->title }}">
                                        </div>

                                        <!-- Thumbnails -->
                                        @if ($product->images->count() > 1)
                                            <div class="row g-2">
                                                @foreach ($product->images as $productImage)
                                                    <div class="col-3">
                                                        <div class="border rounded p-1 cursor-pointer {{ $loop->first ? 'border-primary' : '' }} product-thumbnail-wrapper"
                                                            onclick="changeMainImage('{{ asset('storage/' . $productImage->image_path) }}', this)">
                                                            <img src="{{ asset('storage/' . $productImage->image_path) }}"
                                                                class="img-fluid"
                                                                style="height: 60px; width: 100%; object-fit: cover;"
                                                                alt="Thumbnail">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right Column: Details -->
                                <div class="col-lg-7">
                                    <div class="product-details ml-lg-4">
                                        <h3 class="font-weight-bold mb-3">{{ $product->title }}</h3>

                                        <div class="mb-3">
                                            <span
                                                class="badge badge-info text-uppercase">{{ $product->category->title ?? 'Category' }}</span>
                                            @if ($product->stock > 0)
                                                <span class="badge badge-success ml-2">In Stock</span>
                                            @else
                                                <span class="badge badge-danger ml-2">Out of Stock</span>
                                            @endif
                                        </div>

                                        <h2 class="text-success mb-4">${{ number_format($product->price, 2) }}</h2>

                                        <div class="product-description mb-4 text-muted">
                                            <p>{{ $product->description }}</p>
                                        </div>

                                        <hr>

                                        <form action="{{ route('carts.store', $product) }}" method="POST"
                                            class="mt-4">
                                            @csrf
                                            <div class="row">
                                                <!-- Size Selection -->
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Size</label>
                                                        <select name="size" id="size"
                                                            class="custom-select form-control" required>
                                                            <option value="">Select Size</option>

                                                            @if ($product->category->title === 'Hoodie' || $product->category->title === 'shirts')
                                                                @foreach (['S' => 'Small', 'M' => 'Medium', 'L' => 'Large', 'XL' => 'XL'] as $value => $label)
                                                                    <option value="{{ $value }}"
                                                                        {{ old('size') == $value ? 'selected' : '' }}>
                                                                        {{ $label }}
                                                                    </option>
                                                                @endforeach
                                                            @else
                                                                @foreach (['30', '32', '34', '36'] as $size)
                                                                    <option value="{{ $size }}"
                                                                        {{ old('size') == $size ? 'selected' : '' }}>
                                                                        {{ $size }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>

                                                        @error('size')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror

                                                    </div>
                                                </div>

                                                <!-- Color Selection -->
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Color</label>
                                                        <select name="color" id="color"
                                                            class="custom-select form-control" required>
                                                            <option value="">Select Color</option>
                                                            <option value="White">White</option>
                                                            <option value="Black">Black</option>
                                                            <option value="Green">Green</option>
                                                            <option value="Blue">Blue</option>
                                                            <option value="Red">Red</option>
                                                        </select>
                                                        @error('color')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row align-items-end">
                                                <div class="col-md-4 mb-3">
                                                    <label class="font-weight-bold">Quantity</label>
                                                    <input type="number" name="quantity" id="quantity"
                                                        class="form-control" value="1" min="1"
                                                        max="{{ $product->stock }}" required>
                                                    @error('quantity')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-8 mb-3">
                                                    <div class="d-flex">
                                                        <button type="submit"
                                                            class="btn btn-outline-success btn-lg mr-2 flex-grow-1">
                                                            <i class="ti-shopping-cart"></i> Add to Cart
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-primary btn-lg flex-grow-1"
                                                            onclick="orderNow()">
                                                            Order Now
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- Hidden Order Form -->
                                        <form id="orderNowForm" action="{{ route('orders.direct') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            <input type="hidden" name="product_id" id="order_product_id"
                                                value="{{ $product->id }}">
                                            <input type="hidden" name="size" id="order_size">
                                            <input type="hidden" name="color" id="order_color">
                                            <input type="hidden" name="quantity" id="order_quantity">
                                        </form>

                                        <div class="mt-4">
                                            <p class="mb-2"><small><i class="ti-truck mr-1"></i> Free shipping on
                                                    orders
                                                    over $100</small></p>
                                            <p class="mb-0"><small><i class="ti-shield mr-1"></i> 1 year warranty
                                                    included</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabs Section -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="desc-tab" data-toggle="tab"
                                                href="#desc" role="tab" aria-controls="desc"
                                                aria-selected="true">Description</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews"
                                                role="tab" aria-controls="reviews" aria-selected="false">Reviews
                                                (0)</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content border-left border-right border-bottom p-4"
                                        id="myTabContent">
                                        <div class="tab-pane fade show active" id="desc" role="tabpanel"
                                            aria-labelledby="desc-tab">
                                            <p>{{ $product->description }}</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua.</p>
                                        </div>
                                        <div class="tab-pane fade" id="reviews" role="tabpanel"
                                            aria-labelledby="reviews-tab">
                                            <p class="text-muted">No reviews yet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <script>
                function orderNow() {
                    const size = document.getElementById('size').value;
                    const color = document.getElementById('color').value;
                    const quantity = document.getElementById('quantity').value;

                    if (!size || !color) {
                        alert('Please select size and color before ordering.');
                        return;
                    }

                    document.getElementById('order_size').value = size;
                    document.getElementById('order_color').value = color;
                    document.getElementById('order_quantity').value = quantity;
                    document.getElementById('orderNowForm').submit();
                }

                function changeMainImage(imageSrc, thumbnailWrapper) {
                    document.getElementById('main-product-image').src = imageSrc;

                    // Update active border
                    document.querySelectorAll('.product-thumbnail-wrapper').forEach(function(el) {
                        el.classList.remove('border-primary');
                    });
                    thumbnailWrapper.classList.add('border-primary');
                }
            </script>

            <style>
                .cursor-pointer {
                    cursor: pointer;
                }

                .product-thumbnail-wrapper {
                    transition: all 0.2s;
                }

                .product-thumbnail-wrapper:hover {
                    opacity: 0.8;
                }
            </style>


            <!--=================================
 wrapper -->

            <!--=================================
 footer -->

        </div><!-- main content wrapper end-->
    </div>
    </div>
    </div>

    <!--=================================
 footer -->

    @include('layouts.footer-scripts')

</body>

</html>
