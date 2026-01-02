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

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}<a class=" text-success" href="{{ route('login') }}">
                        <i class="ti-user"></i> Login Now
                    </a>
                </div>
            @endif

            <div class="container my-4">

                <div class="row">
                    <div class="col-md-6">
                        <!-- Product Images Gallery -->
                        @if($product->images->count() > 0)
                            <!-- Main Image -->
                            <div class="mb-3">
                                <img id="main-product-image" 
                                     src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()?->image_path ?? $product->images->first()->image_path)) }}" 
                                     class="img-fluid rounded border" 
                                     alt="{{ $product->title }}"
                                     style="max-height: 500px; width: 100%; object-fit: contain; background-color: #f8f9fa;">
                            </div>
                            
                            <!-- Thumbnail Gallery -->
                            @if($product->images->count() > 1)
                                <div class="row g-2">
                                    @foreach($product->images as $productImage)
                                        <div class="col-3 col-md-2">
                                            <img src="{{ asset('storage/' . $productImage->image_path) }}" 
                                                 class="img-thumbnail product-thumbnail {{ $loop->first ? 'active' : '' }}"
                                                 alt="{{ $product->title }} - Image {{ $loop->iteration }}"
                                                 onclick="changeMainImage('{{ asset('storage/' . $productImage->image_path) }}', this)"
                                                 style="cursor: pointer; height: 80px; width: 100%; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @elseif($product->image)
                            <!-- Fallback to old single image -->
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="img-fluid rounded border" 
                                 alt="{{ $product->title }}"
                                 style="max-height: 500px; width: 100%; object-fit: contain; background-color: #f8f9fa;">
                        @else
                            <div class="alert alert-info">No image available</div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h2>{{ $product->title }}</h2>
                        <p>{{ $product->description }}</p>
                        <h4>Price: ${{ $product->price }}</h4>

                        <!-- فورم لإضافة للـ Cart -->
                        <form action="{{ route('carts.store', $product) }}" method="POST">
                            @csrf
                            <!-- اختيار الـ Size -->
                            @if ($product->category->title === 'shirts')
                                <div class="mb-3">
                                    <label for="size" class="form-label">Size</label>
                                    <select name="size" id="size" class="form-select" required>
                                        <option value="">Select Size</option>
                                        <option value="S">Small</option>
                                        <option value="M">Medium</option>
                                        <option value="L">Large</option>
                                        <option value="XL">XL</option>
                                    </select>
                                    @error('size')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <div class="mb-3">
                                    <label for="size" class="form-label">Size</label>
                                    <select name="size" id="size" class="form-select" required>
                                        <option value="">Select Size</option>
                                        <option value="30">30</option>
                                        <option value="32">32</option>
                                        <option value="34">34</option>
                                        <option value="36">36</option>
                                    </select>
                                    @error('size')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif


                            <!-- اختيار الـ Color -->
                            <div class="mb-3">
                                <label for="color" class="form-label">color</label>
                                <select name="color" id="color" class="form-select" required>
                                    <option value="">Select color</option>
                                    <option value="White">White</option>
                                    <option value="black">black</option>
                                    <option value="green">green</option>
                                </select>
                                @error('color')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الكمية -->
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1"
                                    min="1" max="{{ $product->stock }}" required>
                                @error('quantity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">Add to Cart</button>
                                <button type="button" class="btn btn-primary" onclick="orderNow()">Order Now</button>
                            </div>
                        </form>

                        <!-- Direct Order Form (hidden) -->
                        <form id="orderNowForm" action="{{ route('orders.direct') }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="product_id" id="order_product_id" value="{{ $product->id }}">
                            <input type="hidden" name="size" id="order_size">
                            <input type="hidden" name="color" id="order_color">
                            <input type="hidden" name="quantity" id="order_quantity">
                        </form>

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

                            function changeMainImage(imageSrc, thumbnail) {
                                document.getElementById('main-product-image').src = imageSrc;
                                
                                // Update active thumbnail
                                document.querySelectorAll('.product-thumbnail').forEach(function(thumb) {
                                    thumb.classList.remove('active');
                                    thumb.style.border = '2px solid transparent';
                                });
                                thumbnail.classList.add('active');
                                thumbnail.style.border = '2px solid #007bff';
                            }
                        </script>
                        
                        <style>
                            .product-thumbnail.active {
                                border: 2px solid #007bff !important;
                            }
                            .product-thumbnail:hover {
                                opacity: 0.8;
                            }
                        </style>
                    </div>
                </div>

            </div>


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
