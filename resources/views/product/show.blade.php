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
                    {{ session('error') }}
                </div>
            @endif

            <div class="container my-4">

                <div class="row">
                    <div class="col-md-6">
                        <!-- صورة المنتج -->
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid"
                            alt="{{ $product->title }}">
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
                            <button type="submit" class="btn btn-success">Add to Cart</button>
                        </form>
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
