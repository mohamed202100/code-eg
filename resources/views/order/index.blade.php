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

            <h3 class="mb-4">My Orders</h3>

            @forelse ($orders as $order)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Order #{{ $order->id }}</h6>
                            <small class="text-muted">
                                {{ $order->created_at->format('d M Y') }}
                            </small>
                        </div>

                        <div>
                            <span class="badge badge-warning">{{ ucfirst($order->status) }}</span>
                            <strong class="ms-3">${{ $order->total_price }}</strong>
                        </div>

                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                            View
                        </a>
                    </div>
                </div>
            @empty
                <p>You have no orders yet.</p>
            @endforelse

            {{ $orders->links() }}


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
