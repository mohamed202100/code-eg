<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="Webmin - Bootstrap 4 & Angular 5 Admin Dashboard Template" />
    <meta name="author" content="potenzaglobalsolutions.com" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>About Us - TechSpace Store</title>
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
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}<a class=" text-success" href="{{ route('login') }}">
                        <i class="ti-user"></i> Login Now
                    </a>
                </div>
            @endif
            <div class="page-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="mb-0">About TechSpace</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                            <li class="breadcrumb-item"><a href="/" class="default-color">Home</a></li>
                            <li class="breadcrumb-item active">About Us</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">Who We Are</h5>
                                    <p class="mb-4">
                                        Welcome to <strong>TechSpace Store</strong>, your number one source for all
                                        things electronics. We're dedicated to providing you the best of computer
                                        hardware, software, and accessories, with a focus on dependability, customer
                                        service, and uniqueness.
                                    </p>
                                    <p class="mb-4">
                                        Founded in 2020 by Ahmed Tech, TechSpace has come a long way from its beginnings
                                        in Cairo. When Ahmed first started out, his passion for "Eco-friendly gadgets"
                                        drove him to start his own business.
                                    </p>
                                    <p>
                                        We hope you enjoy our products as much as we enjoy offering them to you. If you
                                        have any questions or comments, please don't hesitate to contact us.
                                    </p>
                                    <br>
                                    <p class="font-weight-bold">
                                        Sincerely,<br>
                                        Ahmed Tech, CEO
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <img src="{{ asset('assets/images/code.jpg') }}" class="img-fluid rounded"
                                        alt="About Us Image">
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="p-3">
                                        <i class="ti-world text-primary display-4 mb-3"></i>
                                        <h5>Global Shipping</h5>
                                        <p>We deliver our products to over 50 countries around the world with fast and
                                            secure shipping.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3">
                                        <i class="ti-shield text-success display-4 mb-3"></i>
                                        <h5>Secure Payments</h5>
                                        <p>Your transactions are safe with us. We use the latest encryption technology.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3">
                                        <i class="ti-headphone-alt text-warning display-4 mb-3"></i>
                                        <h5>24/7 Support</h5>
                                        <p>Our support team is available around the clock to assist you with any
                                            inquiries.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--=================================
 wrapper -->

            <!--=================================
 footer -->

            @include('layouts.footer')
        </div><!-- main content wrapper end-->
    </div>
    </div>
    </div>

    <!--=================================
 footer -->

    @include('layouts.footer-scripts')

</body>

</html>
