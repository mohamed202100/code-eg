<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar start-->
        <div class="side-menu-fixed">
            <div class="scrollbar side-menu-bg">
                <ul class="nav navbar-nav side-menu" id="sidebarnav">
                    <!-- menu item Dashboard-->
                    <li>
                        <a href="/">
                            <div class="pull-left">
                                <i class="ti-home"></i>
                                <span class="right-nav-text">Home</span>
                            </div>
                            <div class="clearfix"></div>
                        </a>
                    </li>

                    <!-- menu title -->
                    <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title">Components </li>
                    <!-- menu item Elements-->
                    <li>
                        <a href="{{ route('categories.index') }}" data-toggle="collapse" data-target="#elements">
                            <div class="pull-left"><i class="ti-palette"></i><span
                                    class="right-nav-text">Category</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        @php
                            use App\Models\Category;
                            $firstThree = Category::orderBy('id', 'asc')->take(1)->get();
                        @endphp
                        <ul id="elements" class="collapse" data-parent="#sidebarnav">
                            <li><a href="{{ route('categories.index') }}">All Categories</a>
                            </li>
                            <li><a
                                    href="{{ route('categories.show', $firstThree[0]->id) }}">{{ $firstThree[0]->title }}</a>
                            </li>

                        </ul>
                    </li>
                    <!-- menu item calendar-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#calendar-menu">
                            <div class="pull-left"><i class="ti-calendar"></i><span
                                    class="right-nav-text">Products</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="calendar-menu" class="collapse" data-parent="#sidebarnav">
                            <li> <a href="{{ route('products.index') }}">All Products </a> </li>
                            @can('create products')
                                <li> <a href="{{ route('products.create') }}">Create Product</a> </li>
                            @endcan
                        </ul>
                    </li>

                    <!-- menu item table -->
                    <li>
                        <a href="{{ route('carts.index') }}">
                            <div class="pull-left">
                                <i class="ti-layout-tab-window"></i>
                                <span class="right-nav-text">Cart</span>
                            </div>
                            <div class="clearfix"></div>
                        </a>
                    </li>

                    <!-- menu item About -->
                    <li>
                        <a href="{{ route('about') }}">
                            <div class="pull-left">
                                <i class="ti-info-alt"></i>
                                <span class="right-nav-text">About Us</span>
                            </div>
                            <div class="clearfix"></div>
                        </a>
                    </li>

                    <!-- menu item Contact -->
                    <li>
                        <a href="{{ route('contact') }}">
                            <div class="pull-left">
                                <i class="ti-headphone-alt"></i>
                                <span class="right-nav-text">Contact Us</span>
                            </div>
                            <div class="clearfix"></div>
                        </a>
                    </li>



                </ul>
            </div>
        </div>

        <!-- Left Sidebar End-->

        <!--=================================