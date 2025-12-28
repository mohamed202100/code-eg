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
            @if ($products->count())
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach ($products as $product)
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <a href="{{ route('products.show', $product->id) }}">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top img-fluid"
                                        alt="{{ $product->title }}" style="height: 220px; object-fit: cover;">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate" title="{{ $product->title }}">
                                        {{ $product->title }}</h5>
                                    <p class="card-text mb-1"><strong>${{ $product->price }}</strong></p>
                                    <div class="mt-auto">
                                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                                            <!-- مسافة بين الأزرار -->
                                            @can('edit products')
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-info flex-grow-1">
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete products')
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                    class="d-inline flex-grow-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger w-100"
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('view products')
                                                <a href="{{ route('products.show', $product->id) }}"
                                                    class="btn btn-sm btn-success d-flex align-items-center justify-content-center flex-grow-1 mt-2">
                                                    <i class="bi bi-cart-plus me-1"></i> Add To Cart
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center">No products in this category.</p>
            @endif


            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
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
