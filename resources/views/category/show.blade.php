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

            <!-- Page Title -->
            <div class="page-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="mb-0">Category: {{ $category->title }}</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                            <li class="breadcrumb-item"><a href="/" class="default-color">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}"
                                    class="default-color">Category</a></li>
                            <li class="breadcrumb-item active">{{ $category->title }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Products in {{ $category->title }}</h5>

                            @if ($products->count())
                                <div class="row">
                                    <!-- Product Card component already contains col-md-4 col-lg-3, so we just loop -->
                                    @foreach ($products as $product)
                                        <x-product-card :product="$product" />
                                    @endforeach
                                </div>

                                @if (method_exists($products, 'links'))
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $products->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <i class="ti-package text-muted display-3 mb-3"></i>
                                    <h5 class="text-muted">No products found in this category.</h5>
                                    @can('create products')
                                        <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">Add Product to
                                            Category</a>
                                    @endcan
                                </div>
                            @endif
                        </div>
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