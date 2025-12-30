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

            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Order #{{ $order->id }}</h4>

                        <span
                            class="badge
                    @if ($order->status == 'pending') bg-warning text-dark
                    @elseif($order->status == 'confirmed') bg-primary
                    @elseif($order->status == 'delivered') bg-success
                    @else bg-danger @endif
                ">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Customer:</strong> {{ $order->full_name }}</p>
                            <p><strong>Phone:</strong> {{ $order->mobile }}</p>
                        </div>

                        <div class="col-md-4">
                            <p><strong>Address:</strong> {{ $order->address }}</p>
                            <p><strong>Created At:</strong> {{ $order->created_at->format('d M Y') }}</p>
                        </div>

                        <div class="col-md-4 text-end">
                            <h5 class="text-success">
                                Total: ${{ number_format($order->total_price, 2) }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Update Status (Admin Only) --}}
            @can('update orders')
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Update Order Status</h5>

                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="pending" @selected($order->status == 'pending')>Pending</option>
                                        <option value="shipped" @selected($order->status == 'shipped')>Shipped</option>
                                        <option value="delivered" @selected($order->status == 'delivered')>Delivered</option>
                                        <option value="refunded" @selected($order->status == 'refunded')>Refunded</option>
                                        <option value="cancelled" @selected($order->status == 'cancelled')>Cancelled</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <button class="btn btn-primary">
                                        Update Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan

            {{-- Order Items --}}
            <h5 class="mb-3">Order Items</h5>

            @foreach ($order->orderItems as $item)
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0 align-items-center">

                        <div class="col-md-2 text-center">
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid rounded"
                                style="max-width: 80px">
                        </div>

                        <div class="col-md-4">
                            <div class="card-body">
                                <h6 class="mb-1">{{ $item->product->title }}</h6>
                                <small class="text-muted">
                                    Size: <strong>{{ $item->size }}</strong> |
                                    Color: <strong>{{ $item->color }}</strong>
                                </small>
                            </div>
                        </div>

                        <div class="col-md-2 text-center">
                            <p class="mb-0">{{ $item->quantity }} pcs</p>
                            <small class="text-muted">Quantity</small>
                        </div>

                        <div class="col-md-2 text-center">
                            <p class="mb-0">${{ $item->price }}</p>
                            <small class="text-muted">Price</small>
                        </div>

                        <div class="col-md-2 text-center">
                            <p class="mb-0 fw-bold">
                                ${{ $item->price * $item->quantity }}
                            </p>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            @endforeach
            <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-sm btn-primary">
                Download Invoice
            </a>


            {{-- Back --}}
            <div class="text-center mt-4">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    Back to Orders
                </a>
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
