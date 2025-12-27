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


            <h2>Your Cart</h2>

            @if ($cart && $cart->cartItems->count() > 0)
                @foreach ($cart->cartItems as $item)
                    <div class="card mb-3 shadow-sm">
                        <div class="row g-0 align-items-center">
                            <!-- Image -->
                            <div class="col-md-2 text-center">
                                <a href="{{ route('products.show', $item->product->id) }}">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid rounded"
                                        style="max-width: 80px" alt="{{ $item->product->title }}">
                                </a>
                            </div>

                            <!-- Product Info -->
                            <div class="col-md-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-1">{{ $item->product->title }}({{ $item->quantity }} pcs)
                                    </h5>
                                    </h5>
                                    <small class="text-muted">
                                        Size: <strong>{{ $item->size }}</strong> |
                                        Color: <strong>{{ $item->color }}</strong>
                                    </small>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-md-2 text-center">
                                <p>Total: ${{ $item->price * $item->quantity }}</p>
                            </div>

                            <!-- Actions -->
                            <form action="{{ route('cartItems.increment', $item->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">+</button>
                            </form>

                            <form action="{{ route('cartItems.decrement', $item->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-warning">-</button>
                            </form>

                            <div class="col-md-2 text-center">
                                <form action="{{ route('cartItems.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Remove this item?')">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
                <div class="text-center my-4">
                    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-lg">Order Now</a>
                </div>
            @else
                <p class="text-center">Your cart is empty!</p>
                <div class="text-center mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Shop Now
                    </a>
                </div>
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
