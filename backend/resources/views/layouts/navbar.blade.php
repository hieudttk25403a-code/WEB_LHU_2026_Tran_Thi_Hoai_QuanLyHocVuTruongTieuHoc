<div class="navbar-left">

    <h4 class="mb-0">
        Dashboard
    </h4>

</div>

<div class="navbar-right d-flex align-items-center">

    <button class="notification-btn me-3">

        <i class="fa-solid fa-bell"></i>

        <span class="badge bg-danger">3</span>

    </button>

    <div class="user-info d-flex align-items-center me-3">

        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563EB&color=fff"
             alt="Avatar">

        <span class="ms-2">{{ Auth::user()->name }}</span>

    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" class="btn btn-outline-danger btn-sm">

            <i class="fa-solid fa-right-from-bracket"></i>

            Đăng xuất

        </button>
    </form>

</div>