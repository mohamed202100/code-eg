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

            <h2>{{ $category->title }}</h2>

            <h4>Products</h4>

            @if ($products->count())
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <a href="{{ route('products.show', $product->id) }}">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                        alt="{{ $product->title }}">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->title }}</h5>
                                    <p class="card-text">${{ $product->price }}</p>
                                    @can('view products')
                                        <a href="{{ route('products.show', $product->id) }}"
                                            class="btn btn-success d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-cart-plus"></i>
                                            Add To Cart
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (method_exists($products, 'links'))
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <p>No products found in this category.</p>
            @endif


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
