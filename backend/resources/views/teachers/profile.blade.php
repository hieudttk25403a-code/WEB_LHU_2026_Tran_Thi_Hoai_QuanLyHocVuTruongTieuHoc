<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Thông tin cá nhân</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>

        body {
            background-color: #f5f7fa;
        }

        .topbar {
            background: #198754;
            color: white;
            padding: 15px 25px;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .profile-title {
            color: #198754;
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
        }

    </style>

</head>


<body>

{{-- =========================================================
     TOPBAR
========================================================= --}}

<div class="topbar">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="mb-0">

                    <i class="fa-solid fa-school me-2"></i>

                    Hệ thống quản lý học vụ

                </h5>

            </div>


            <div>

                <span class="me-3">

                    <i class="fa-solid fa-user me-1"></i>

                    {{ $user->name }}

                </span>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="d-inline"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-light btn-sm"
                    >

                        <i class="fa-solid fa-right-from-bracket me-1"></i>

                        Đăng xuất

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     CONTENT
========================================================= --}}

<div class="container py-4">

    <div class="profile-card">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="profile-title mb-1">

                    <i class="fa-solid fa-user me-2"></i>

                    Thông tin cá nhân

                </h3>

                <p class="text-muted mb-0">

                    Xem và cập nhật thông tin cá nhân của bạn.

                </p>

            </div>


            <a
                href="{{ route('teacher.dashboard') }}"
                class="btn btn-outline-success"
            >

                <i class="fa-solid fa-arrow-left me-1"></i>

                Quay lại

            </a>

        </div>


        {{-- =================================================
             THÔNG BÁO
        ================================================== --}}

        @if(session('success'))

            <div class="alert alert-success">

                <i class="fa-solid fa-circle-check me-2"></i>

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger">

                <i class="fa-solid fa-circle-exclamation me-2"></i>

                {{ session('error') }}

            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Vui lòng kiểm tra lại:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        @if(!$teacher)

            <div class="alert alert-warning">

                <i class="fa-solid fa-triangle-exclamation me-2"></i>

                Tài khoản này chưa được liên kết với hồ sơ giáo viên.

            </div>

        @else

            {{-- =================================================
                 THÔNG TIN TÀI KHOẢN
            ================================================== --}}

            <h5 class="mb-3">

                <i class="fa-solid fa-lock me-2 text-success"></i>

                Thông tin tài khoản

            </h5>


            <div class="row mb-4">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Email đăng nhập
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        value="{{ $user->email }}"
                        readonly
                    >

                    <small class="text-muted">
                        Email đăng nhập được cấp bởi quản trị viên.
                    </small>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Quyền tài khoản
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="Giáo viên"
                        readonly
                    >

                </div>

            </div>


            <hr class="my-4">


            {{-- =================================================
                 THÔNG TIN GIÁO VIÊN
            ================================================== --}}

            <h5 class="mb-3">

                <i class="fa-solid fa-id-card me-2 text-success"></i>

                Thông tin giáo viên

            </h5>


            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Mã giáo viên
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $teacher->teacher_code }}"
                        readonly
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Họ và tên
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $teacher->full_name }}"
                        readonly
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        value="{{ $teacher->email }}"
                        readonly
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Số điện thoại
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $teacher->phone ?: 'Chưa cập nhật' }}"
                        readonly
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Chuyên môn
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $teacher->defaultSpecialization() }}"
                        readonly
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Trạng thái
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $teacher->status }}"
                        readonly
                    >

                </div>

            </div>


            <div class="alert alert-info mt-3">

                <i class="fa-solid fa-circle-info me-2"></i>

                Một số thông tin hồ sơ do quản trị viên quản lý.
                Giáo viên không thể tự thay đổi mã giáo viên,
                email hoặc thông tin chuyên môn.

            </div>


            {{-- =================================================
                 ĐỔI MẬT KHẨU
            ================================================== --}}

            <hr class="my-4">

            <h5 class="mb-3">

                <i class="fa-solid fa-key me-2 text-success"></i>

                Đổi mật khẩu

            </h5>


<form
    method="POST"
    action="{{ route('teachers.password.update') }}"
>

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Mật khẩu hiện tại
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            class="form-control"
                            placeholder="Nhập mật khẩu hiện tại"
                        >

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Mật khẩu mới
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Nhập mật khẩu mới"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Xác nhận mật khẩu mới
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Nhập lại mật khẩu mới"
                        >

                    </div>

                </div>


<button
    type="submit"
    class="btn btn-success"
>

                    <i class="fa-solid fa-key me-1"></i>

                    Đổi mật khẩu

                </button>


            </form>

        @endif

    </div>

</div>


</body>

</html>