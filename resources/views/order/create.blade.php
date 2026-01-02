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
            <div class="container mt-5">
                <h2 class="mb-4">Checkout</h2>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    @if(isset($isDirectOrder) && $isDirectOrder)
                        <input type="hidden" name="direct_order" value="1">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size" value="{{ $cart->cartItems->first()->size }}">
                        <input type="hidden" name="color" value="{{ $cart->cartItems->first()->color }}">
                        <input type="hidden" name="quantity" value="{{ $cart->cartItems->first()->quantity }}">
                    @endif

                    <!-- الاسم -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        @if (Auth::check())
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ Auth::user()->name }}" readonly>
                        @else
                            @php
                                $guestName = \App\Helpers\SessionCartHelper::getGuestName();
                            @endphp
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $guestName) }}"
                                placeholder="Enter your name" required>
                        @endif
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- رقم الهاتف -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            placeholder="Enter your phone number" required>
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- العنوان -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Enter your address" required></textarea>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <input class="p-3 bg-light border rounded text-end fw-bold" type="text" name="total"
                        value="{{ $total }}" readonly>



                    <!-- ستاتس افتراضي -->
                    <input type="hidden" name="status" value="pending">

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Place Order</button>
                    </div>
                </form>


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
