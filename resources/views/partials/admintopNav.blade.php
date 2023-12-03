<nav class="navbar navbar-expand-sm bg-light justify-content-end">
    <ul class="navbar-nav">
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
                    <li><a class="dropdown-item" href="{{ route('user.logout') }}">Logout</a></li>
                @else

                @endif
            </ul>
        </div>
    </ul>
  </nav>