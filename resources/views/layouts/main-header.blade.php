<!--=================================
 header start-->
<nav class="admin-header navbar navbar-default col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <!-- logo -->
    <div class="text-left navbar-brand-wrapper">
        <a class="navbar-brand brand-logo d-flex align-items-center" href="#">
            <img src="{{ asset('assets/images/code.jpg') }}" alt="Logo" class="me-2"
                style="height:50px; width:50px; object-fit:cover; border-radius:8px;">
        </a>
        <a class="navbar-brand brand-logo-mini" href="#">
            <img src="{{ asset('assets/images/code.jpg') }}" alt="Logo Mini"
                style="height:40px; width:40px; object-fit:cover; border-radius:6px;">
        </a>
    </div>

    <!-- Top bar left -->
    <ul class="nav navbar-nav mr-auto">
        <li class="nav-item">
            <a id="button-toggle" class="button-toggle-nav inline-block ml-20 pull-left" href="javascript:void(0);"><i
                    class="zmdi zmdi-menu ti-align-right"></i></a>
        </li>
        <li class="nav-item">
            <div class="search">
                <a class="search-btn not_click" href="javascript:void(0);"></a>
                <form action="{{ route('products.search') }}" method="GET">
                    <div class="search-box not-click">
                        <input type="text" class="not-click form-control" placeholder="Search" name="query">
                        <button class="search-button" type="submit">
                            <i class="fa fa-search not-click"></i>
                        </button>
                    </div>
                </form>

            </div>
        </li>
    </ul>
    <!-- top bar right -->
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item fullscreen">
            <a id="btnFullscreen" href="#" class="nav-link"><i class="ti-fullscreen"></i></a>
        </li>
        @auth
            <li class="nav-item dropdown">
                <a class="nav-link top-nav" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="ti-bell"></i>
                    <span class="badge badge-danger notification-status">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-big dropdown-notifications">
                    <div class="dropdown-header notifications">
                        <strong>Notifications</strong>
                        <span class="badge badge-pill badge-warning">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </div>
                    <div class="dropdown-divider"></div>

                    @forelse(auth()->user()->unreadNotifications as $notification)
                        @if (auth()->user()->role == 'admin')
                            <a href="{{ route('admin.orders.show', $notification->data['order_id'] ?? '#') }}"
                                class="dropdown-item">
                        @else
                                <a href="{{ route('orders.show', $notification->data['order_id'] ?? '#') }}" class="dropdown-item">
                            @endif
                                {{ $notification->data['message'] ?? 'New Notification' }}
                                <small class="float-right text-muted time">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </a>
                    @empty
                            <a href="#" class="dropdown-item text-center text-muted">
                                No new notifications
                            </a>
                        @endforelse
                </div>
            </li>
        @endauth


        <li class="nav-item dropdown mr-30">
            <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <img src="assets/images/profile-avatar.png" alt="avatar">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header">
                    <div class="media">
                        <div class="media-body">
                            <h5 class="mt-0 mb-0">
                                @if (Auth::check())
                                    <p>{{ Auth::user()->name }}</p>
                                    <span class="text-muted small">{{ Auth::user()->email }}</span>
                                @else
                                    <p>guest!</p>
                                @endif
                            </h5>

                        </div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                        class="text-warning ti-user"></i>Profile</a>

                <a class="dropdown-item" href="{{ route('orders.index') }}">
                    <i class="text-dark ti-layers-alt"></i>
                    Orders
                    <span class="badge badge-info">
                        {{ Auth::check() ? Auth::user()->orders()->count() : 0 }}
                    </span>
                </a>
                <div class="dropdown-divider"></div>
                @if (Auth::check())
                    <a class="dropdown-item text-danger" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ti-unlock"></i> Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <a class="dropdown-item text-success" href="{{ route('login') }}">
                        <i class="ti-user"></i> Login
                    </a>
                @endif

            </div>
        </li>
    </ul>
</nav>

<!--=================================
 header End-->