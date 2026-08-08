<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập hệ thống</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<div class="login-page">

    <div class="login-card">

        <div class="text-center">

            <i class="fa-solid fa-book-open-reader login-icon"></i>

            <h1>Đăng Nhập Hệ Thống</h1>

            <p>Trường Tiểu học Tân Lập 3</p>

        </div>

        <x-auth-session-status class="mb-3" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">

            @csrf

            <div class="mb-3">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control"
                    required
                    autofocus>

                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            <div class="mb-3">

                <label>Mật khẩu</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required>

                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <input
                        type="checkbox"
                        name="remember">

                    Ghi nhớ đăng nhập

                </div>

                @if (Route::has('password.request'))

                    <a href="{{ route('password.request') }}">

                        Quên mật khẩu?

                    </a>

                @endif

            </div>

            <button class="btn btn-primary w-100">

                Đăng nhập

            </button>

        </form>

    </div>

</div>

</body>

</html>