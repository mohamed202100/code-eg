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
            <div class="page-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="mb-0"> Dashboard</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                        </ol>
                    </div>
                </div>
            </div>
            <!-- widgets -->
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <div class="clearfix">
                                <div class="float-left">
                                    <span class="text-danger">
                                        <i class="fa fa-bar-chart-o highlight-icon" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="float-right text-right">
                                    <p class="card-text text-dark">Visitors</p>
                                    <h4>65,650</h4>
                                </div>
                            </div>
                            <p class="text-muted pt-3 mb-0 mt-2 border-top">
                                <i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> 81% lower
                                growth
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <div class="clearfix">
                                <div class="float-left">
                                    <span class="text-warning">
                                        <i class="fa fa-shopping-cart highlight-icon" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="float-right text-right">
                                    @can('update orders')
                                        <a href="{{ route('admin.orders.index') }}">
                                            <p class="card-text text-dark">Orders</p>
                                            <h4>{{ \App\Models\Order::count() }}</h4>
                                        </a>
                                    @else
                                        <p class="card-text text-dark">Orders</p>
                                        <h4>{{ \App\Models\Order::count() }}</h4>
                                    @endcan
                                </div>

                            </div>
                            <p class="text-muted pt-3 mb-0 mt-2 border-top">
                                <i class="fa fa-bookmark-o mr-1" aria-hidden="true"></i> Total sales
                                <span class="text-muted mb-0 float-right text-right">
                                    ${{ \App\Models\Order::sum('total_price') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <div class="clearfix">
                                <div class="float-left">
                                    <span class="text-success">
                                        <i class="fa fa-dollar highlight-icon" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="float-right text-right">
                                    <p class="card-text text-dark">Revenue</p>
                                    <h4>$65656</h4>
                                </div>
                            </div>
                            <p class="text-muted pt-3 mb-0 mt-2 border-top">
                                <i class="fa fa-calendar mr-1" aria-hidden="true"></i> Sales Per Year
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <div class="clearfix">
                                <div class="float-left">
                                    <span class="text-primary">
                                        <i class="fa fa-twitter highlight-icon" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="float-right text-right">
                                    <p class="card-text text-dark">Followers</p>
                                    <h4>62,500+</h4>
                                </div>
                            </div>
                            <p class="text-muted pt-3 mb-0 mt-2 border-top">
                                <i class="fa fa-repeat mr-1" aria-hidden="true"></i> Just Updated
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Orders Status widgets-->
            <div class="row">
                <div class="col-xl-4 mb-30">
                    <div class="card card-statistics h-100">
                        <!-- action group -->
                        <div class="btn-group info-drop">
                            <button type="button" class="dropdown-toggle-split text-muted" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"><i class="ti-more"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#"><i
                                        class="text-primary ti-reload"></i>Refresh</a>
                                <a class="dropdown-item" href="#"><i class="text-secondary ti-eye"></i>View
                                    all</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Market summary</h5>
                            <h4>$50,500 </h4>
                            <div class="row mt-20">
                                <div class="col-4">
                                    <h6>Apple</h6>
                                    <b class="text-info">+ 82.24 % </b>
                                </div>
                                <div class="col-4">
                                    <h6>Instagram</h6>
                                    <b class="text-danger">- 12.06 % </b>
                                </div>
                                <div class="col-4">
                                    <h6>Google</h6>
                                    <b class="text-warning">+ 24.86 % </b>
                                </div>
                            </div>
                        </div>
                        <div id="sparkline2" class="scrollbar-x text-center"></div>
                    </div>
                </div>
                <div class="col-xl-8 mb-30">
                    <div class="card h-100">
                        <div class="btn-group info-drop">
                            <button type="button" class="dropdown-toggle-split text-muted" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"><i class="ti-more"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#"><i
                                        class="text-primary ti-reload"></i>Refresh</a>
                                <a class="dropdown-item" href="#"><i class="text-secondary ti-eye"></i>View
                                    all</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-block d-md-flexx justify-content-between">
                                <div class="d-block">
                                    <h5 class="card-title">Site Visits Growth </h5>
                                </div>
                                <div class="d-flex">
                                    <div class="clearfix mr-30">
                                        <h6 class="text-success">Income</h6>
                                        <p>+584</p>
                                    </div>
                                    <div class="clearfix  mr-50">
                                        <h6 class="text-danger"> Outcome</h6>
                                        <p>-255</p>
                                    </div>
                                </div>
                            </div>
                            <div id="morris-area" style="height: 320px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <h5 class="card-title">Best Selling Items</h5>
                            <ul class="list-unstyled">
                                <li class="mb-20">
                                    <div class="media">
                                        <div class="position-relative">
                                            <img class="img-fluid mr-15 avatar-small" src="images/item/01.png"
                                                alt="">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-0">Car dealer <span class="float-right text-danger">
                                                    8,561</span> </h6>
                                            <p>Automotive WordPress Theme </p>
                                        </div>
                                    </div>
                                    <div class="divider dotted mt-20"></div>
                                </li>
                                <li class="mb-20">
                                    <div class="media">
                                        <div class="position-relative clearfix">
                                            <img class="img-fluid mr-15 avatar-small" src="images/item/02.png"
                                                alt="">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-0">Webster <span class="float-right text-warning">
                                                    6,213</span> </h6>
                                            <p>Multi-purpose HTML5 Template </p>
                                        </div>
                                    </div>
                                    <div class="divider dotted mt-20"></div>
                                </li>
                                <li class="mb-20">
                                    <div class="media">
                                        <div class="position-relative">
                                            <img class="img-fluid mr-15 avatar-small" src="images/item/03.png"
                                                alt="">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-0">The corps <span class="float-right text-success">
                                                    2,926</span> </h6>
                                            <p> Multi-Purpose WordPress Theme </p>
                                        </div>
                                    </div>
                                    <div class="divider dotted mt-20"></div>
                                </li>
                                <li>
                                    <div class="media">
                                        <div class="position-relative clearfix">
                                            <img class="img-fluid mr-15 avatar-small" src="images/item/04.png"
                                                alt="">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-0">Sam martin <span
                                                    class="float-right text-warning">6,213 </span></h6>
                                            <p>Personal vCard Resume WordPress Theme </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="p-4 text-center bg" style="background: url(images/bg/01.jpg);">
                            <h5 class="mb-70 text-white position-relative">Michael Bean </h5>
                            <div class="btn-group info-drop">
                                <button type="button" class="dropdown-toggle-split text-white"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ti-more"></i></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#"><i class="text-primary ti-files"></i> Add
                                        task</a>
                                    <a class="dropdown-item" href="#"><i class="text-dark ti-pencil-alt"></i>
                                        Edit Profile</a>
                                    <a class="dropdown-item" href="#"><i class="text-success ti-user"></i> View
                                        Profile</a>
                                    <a class="dropdown-item" href="#"><i class="text-secondary ti-info"></i>
                                        Contact Info</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center position-relative">
                            <div class="avatar-top">
                                <img class="img-fluid w-25 rounded-circle " src="images/team/13.jpg" alt="">
                            </div>
                            <div class="row">
                                <div class="col-sm-4 mt-30">
                                    <b>Files Saved</b>
                                    <h4 class="text-success mt-10">1582</h4>
                                </div>
                                <div class="col-sm-4 mt-30">
                                    <b>Memory Used </b>
                                    <h4 class="text-danger mt-10">58GB</h4>
                                </div>
                                <div class="col-sm-4 mt-30">
                                    <b>Spent Money</b>
                                    <h4 class="text-warning mt-10">352,6$</h4>
                                </div>
                            </div>
                            <div class="divider mt-20"></div>
                            <p class="mt-30">17504 Carlton Cuevas Rd, Gulfport, MS, 39503</p>
                            <p class="mt-10">michael@webmin.com</p>
                            <div class="social-icons color-icon mt-20">
                                <ul>
                                    <li class="social-rss"><a href="#"><i class="fa fa-rss"></i></a></li>
                                    <li class="social-facebook"><a href="#"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li class="social-twitter"><a href="#"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li class="social-github"><a href="#"><i class="fa fa-github"></i></a></li>
                                    <li class="social-youtube"><a href="#"><i class="fa fa-youtube"></i></a>
                                    </li>
                                    <li class="social-instagram"><a href="#"><i
                                                class="fa fa-instagram"></i></a>
                                    </li>
                                </ul>
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
