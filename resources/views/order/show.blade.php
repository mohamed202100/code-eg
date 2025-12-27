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

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Order Info --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h4 class="mb-2">Order #{{ $order->id }}</h4>
                    <p class="mb-1">
                        <strong>Status:</strong>
                        <span class="badge bg-warning text-dark">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p class="mb-0">
                        <strong>Total:</strong> ${{ $order->total_price }}
                    </p>
                </div>
            </div>

            {{-- Order Items --}}
            <h5 class="mb-3">Order Items</h5>

            @foreach ($order->orderItems as $item)
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0 align-items-center">

                        <!-- Image -->
                        <div class="col-md-2 text-center">
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid rounded"
                                style="max-width: 80px" alt="{{ $item->product->title }}">
                        </div>

                        <!-- Info -->
                        <div class="col-md-4">
                            <div class="card-body">
                                <h6 class="mb-1">{{ $item->product->title }}</h6>
                                <small class="text-muted">
                                    Size: <strong>{{ $item->size }}</strong> |
                                    Color: <strong>{{ $item->color }}</strong>
                                </small>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-2 text-center">
                            <p class="mb-0">{{ $item->quantity }} pcs</p>
                            <small class="text-muted">Quantity</small>
                        </div>

                        <!-- Price -->
                        <div class="col-md-2 text-center">
                            <p class="mb-0">${{ $item->price }}</p>
                            <small class="text-muted">Price</small>
                        </div>

                        <!-- Total -->
                        <div class="col-md-2 text-center">
                            <p class="mb-0 fw-bold">
                                ${{ $item->price * $item->quantity }}
                            </p>
                            <small class="text-muted">Total</small>
                        </div>

                    </div>
                </div>
            @endforeach

            {{-- Back Button --}}
            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    Continue Shopping
                </a>
            </div>

        </div>
    </div>

    @include('layouts.footer-scripts')
</body>

</html>
