<div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
    <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
        <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-5 d-none d-sm-inline">Espresso Oasis</span>
        </a>
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link align-middle text-white px-0">
                    <i class="fa-solid fa-chart-column" style="color: #ffffff;"></i>
                    <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->hasSuperAdminRole())
                <li>
                    <a href="{{ route('products') }}" class="nav-link text-white px-0 align-middle">
                        <i class="fa-solid fa-shop" style="color: #ffffff;"></i>
                        <span class="ms-1 d-none d-sm-inline">Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders') }}" class="nav-link text-white px-0 align-middle">
                        <i class="fa-solid fa-list" style="color: #ffffff;"></i>
                        <span class="ms-1 d-none d-sm-inline">Orders</span>
                    </a>
                </li>
                <a href="{{ route('accounts') }}" class="nav-link text-white px-0 align-middle ">
                    <i class="fa-solid fa-users" style="color: #ffffff;"></i>
                    <span class="ms-1 d-none d-sm-inline">Account Management</span>
                </a>
                </li>
            @endif

            @if (auth()->user()->hasAdminRole())
            <li>
                <a href="{{ route('products') }}" class="nav-link text-white px-0 align-middle">
                    <i class="fa-solid fa-shop" style="color: #ffffff;"></i>
                    <span class="ms-1 d-none d-sm-inline">Products</span>
                </a>
            </li>
            <li>
                <a href="{{ route('orders') }}" class="nav-link text-white px-0 align-middle">
                    <i class="fa-solid fa-list" style="color: #ffffff;"></i>
                    <span class="ms-1 d-none d-sm-inline">Orders</span>
                </a>
            </li>
            @endif

            @if (auth()->user()->hasAttendantRole())
            <li>
                <a href="{{ route('orders') }}" class="nav-link text-white px-0 align-middle">
                    <i class="fa-solid fa-list" style="color: #ffffff;"></i>
                    <span class="ms-1 d-none d-sm-inline">Orders</span>
                </a>
            </li>
            @endif
        </ul>

        <hr>
    </div>
</div>
