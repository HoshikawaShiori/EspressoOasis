<nav class="navbar navbar-expand-lg bg-white">
    <div class="container">
        <a class="navbar-brand" href="#"><img src="{{ asset('src/images/logo2.png') }}" alt="Fucking navbar layout"
                class="img-fluid" width="200" height="200"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('coffee.shop')}}">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
            </ul>
            <div class="d-flex">
                <a href="{{ route('coffee.cart') }}" class="btn btn-light me-3 border-0" style="background-color: transparent; text-decoration: none;">
                    <i class="fa-solid fa-cart-shopping"></i> Cart
                        <span class="badge">&#40;{{Session::has('cart') ? Session::get('cart')->totalQty : ''}}&#41;</span>
                </a>
                
                <div class="dropdown">

                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i> User
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @if(Auth::check())
                            <li><a class="dropdown-item" href="{{route('user.profile')}}">Profile</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a class="dropdown-item" href="{{route('user.logout')}}">Logout</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{route('user.signup')}}">Sign up</a></li>
                            <li><a class="dropdown-item" href="{{route('user.signin')}}">Login</a></li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </div>
</nav>
