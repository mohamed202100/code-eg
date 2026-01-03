<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="Webmin - Bootstrap 4 & Angular 5 Admin Dashboard Template" />
    <meta name="author" content="potenzaglobalsolutions.com" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Contact Us - TechSpace Store</title>
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
                        <h4 class="mb-0">Contact Us</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                            <li class="breadcrumb-item"><a href="/" class="default-color">Home</a></li>
                            <li class="breadcrumb-item active">Contact Us</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <h5 class="card-title">Get in Touch</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <form>
                                        <div class="form-group mb-3">
                                            <label>Your Name</label>
                                            <input type="text" class="form-control" placeholder="Enter your name">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" placeholder="Enter your email">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Subject</label>
                                            <input type="text" class="form-control" placeholder="Subject">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Message</label>
                                            <textarea class="form-control" rows="5" placeholder="Your Message"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Send Message</button>
                                    </form>
                                </div>
                                <div class="col-md-6 pl-md-5">
                                    <h5 class="mb-4 mt-4 mt-md-0">Contact Information</h5>

                                    <div class="d-flex align-items-center mb-4">
                                        <div class="icon-box bg-light rounded-circle text-center d-flex align-items-center justify-content-center mr-3"
                                            style="width: 50px; height: 50px;">
                                            <i class="ti-location-pin text-primary" style="font-size: 20px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Address</h6>
                                            <p class="mb-0 text-muted">123 Tech Avenue, Nasr City, Cairo, Egypt</p>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center mb-4">
                                        <div class="icon-box bg-light rounded-circle text-center d-flex align-items-center justify-content-center mr-3"
                                            style="width: 50px; height: 50px;">
                                            <i class="ti-mobile text-success" style="font-size: 20px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Phone</h6>
                                            <p class="mb-0 text-muted">+20 100 123 4567</p>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center mb-4">
                                        <div class="icon-box bg-light rounded-circle text-center d-flex align-items-center justify-content-center mr-3"
                                            style="width: 50px; height: 50px;">
                                            <i class="ti-email text-warning" style="font-size: 20px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Email</h6>
                                            <p class="mb-0 text-muted">support@techspace.com</p>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <h6 class="mb-3">Follow Us</h6>
                                        <div class="social-icons">
                                            <a href="#"
                                                class="btn btn-sm btn-outline-primary rounded-circle mr-1"><i
                                                    class="ti-facebook"></i></a>
                                            <a href="#" class="btn btn-sm btn-outline-info rounded-circle mr-1"><i
                                                    class="ti-twitter"></i></a>
                                            <a href="#"
                                                class="btn btn-sm btn-outline-danger rounded-circle mr-1"><i
                                                    class="ti-instagram"></i></a>
                                            <a href="#" class="btn btn-sm btn-outline-dark rounded-circle"><i
                                                    class="ti-linkedin"></i></a>
                                        </div>
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
