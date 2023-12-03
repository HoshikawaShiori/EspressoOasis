<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: transparent;" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('coffee.index') }}"><img src="{{ asset('src/images/logo2.png') }}" alt="navbar layout"
                class="img-fluid" width="200" height="200"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation" style="color: #D19B6C;">
            <span><i class="fa-solid fa-bars" style="color: #D19B6C;"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('coffee.shop')}}">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
            <div class="d-flex">
                @if(Auth::check())
                <a href="{{ route('coffee.cart') }}" class="btn btn-light me-3 border-0" style="background-color: transparent; text-decoration: none;">
                    <i class="fa-solid fa-cart-shopping"></i> Cart
                        <span class="badge">&#40;{{Session::has('cart') ? Session::get('cart')->totalQty : ''}}&#41;</span>
                </a>
                @endif
                
                
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                        @if(Auth::check())
                            {{ Auth::user()->username }}
                        @else
                            User
                        @endif
                    </button>
                    <ul class="dropdown-menu bg-light" aria-labelledby="dropdownMenuButton">
                        @if(Auth::check())
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a class="dropdown-item" href="{{ route('user.logout') }}">Logout</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('user.signup') }}">Sign up</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.signin') }}">Login</a></li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </div>
</nav>

@section('scripts')
<script>
    $(document).ready(function() {

        $(window).scroll(function() {
            var navbar = $("#navbar");
            if ($(window).scrollTop() > 50) {
                navbar.removeClass("navbar-transparent").addClass("bg-light");
            } else {
                navbar.removeClass("bg-light").addClass("navbar-transparent");
            }
        });
    });
</script>
@endsection