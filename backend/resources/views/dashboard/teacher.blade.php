<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard Giáo viên</title>

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

        .welcome-card,
        .feature-card {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .welcome-card {
            padding: 25px;
        }

        .feature-card {
            padding: 25px;
            height: 100%;
            transition: 0.2s;
        }

        .feature-card:hover {
            transform: translateY(-3px);
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e8f5e9;
            color: #198754;
            font-size: 23px;
            margin-bottom: 15px;
        }

        .teacher-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
        }

        .feature-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
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
                    {{ auth()->user()->name }}
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

<div class="container-fluid py-4 px-4">

    {{-- Chào giáo viên --}}

    <div class="welcome-card mb-4">

        <div class="row align-items-center">

            <div class="col-md-8">

                <h3 class="mb-2">
                    Xin chào, {{ auth()->user()->name }}! 👋
                </h3>

                <p class="text-muted mb-0">
                    Chào mừng bạn đến với hệ thống quản lý học vụ.
                </p>

            </div>

            <div class="col-md-4 text-md-end mt-3 mt-md-0">

                <span class="badge bg-success fs-6">
                    <i class="fa-solid fa-chalkboard-user me-1"></i>
                    Giáo viên
                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         THÔNG TIN CÁ NHÂN
    ====================================================== --}}

    @if(auth()->user()->teacher)

        @php
            $teacher = auth()->user()->teacher;
        @endphp

        <div class="welcome-card mb-4">

            <h5 class="mb-3">
                <i class="fa-solid fa-id-card me-2 text-success"></i>
                Thông tin cá nhân
            </h5>

            <div class="teacher-info">

                <div class="row">

                    <div class="col-md-4 mb-2">
                        <strong>Mã giáo viên:</strong>
                        {{ $teacher->teacher_code }}
                    </div>

                    <div class="col-md-4 mb-2">
                        <strong>Họ và tên:</strong>
                        {{ $teacher->full_name }}
                    </div>

                    <div class="col-md-4 mb-2">
                        <strong>Email:</strong>
                        {{ $teacher->email ?: 'Chưa cập nhật' }}
                    </div>

                    <div class="col-md-4 mb-2">
                        <strong>Điện thoại:</strong>
                        {{ $teacher->phone ?: 'Chưa cập nhật' }}
                    </div>

                    <div class="col-md-4 mb-2">
                        <strong>Chuyên môn:</strong>
                        {{ $teacher->defaultSpecialization() }}
                    </div>

                    <div class="col-md-4 mb-2">
                        <strong>Trạng thái:</strong>
                        {{ $teacher->status }}
                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =====================================================
         CHỨC NĂNG GIÁO VIÊN
    ====================================================== --}}

    <h5 class="mb-3">
        <i class="fa-solid fa-graduation-cap me-2 text-success"></i>
        Chức năng dành cho giáo viên
    </h5>


    <div class="row g-4">

        {{-- =================================================
             THÔNG TIN CÁ NHÂN
        ================================================== --}}

        <div class="col-md-6 col-lg-4">

<a
    href="{{ route('teachers.profile') }}"
    class="feature-link"
>

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <h5>
                        Thông tin cá nhân
                    </h5>

                    <p class="text-muted mb-0">
                        Xem và cập nhật thông tin cá nhân,
                        thay đổi mật khẩu tài khoản.
                    </p>

                </div>

            </a>

        </div>


        {{-- =================================================
             QUẢN LÝ GIẢNG DẠY
        ================================================== --}}

        <div class="col-md-6 col-lg-4">

            <a
                href="{{ route('teacher.teaching.schedule') }}"
                class="feature-link"
            >

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>

                    <h5>
                        Quản lý giảng dạy
                    </h5>

                    <p class="text-muted mb-0">
                        Xem lịch giảng dạy, danh sách lớp được
                        phân công và điểm danh học sinh.
                    </p>

                </div>

            </a>

        </div>


        {{-- =================================================
             QUẢN LÝ ĐIỂM SỐ
        ================================================== --}}

        <div class="col-md-6 col-lg-4">

            <a
                href="{{ route('teacher.scores.index') }}"
                class="feature-link"
            >

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>

                    <h5>
                        Quản lý điểm số
                    </h5>

                    <p class="text-muted mb-0">
                        Nhập điểm, cập nhật điểm và theo dõi
                        kết quả học tập của học sinh.
                    </p>

                </div>

            </a>

        </div>

        <!-- ĐIỂM DANH -->
<div class="col-md-6 col-lg-4">
    <a href="{{ route('teacher.attendance.index') }}"
       class="text-decoration-none text-dark">

        <div class="card h-100 border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <div class="d-flex align-items-center justify-content-center
                            rounded-4 mb-3"
                     style="
                        width: 56px;
                        height: 56px;
                        background-color: #e8f5e9;
                     ">

                    <i class="fas fa-user-check fs-3 text-success"></i>

                </div>

                <h4 class="fw-bold mb-2">
                    Điểm danh
                </h4>

                <p class="text-muted mb-0">
                    Điểm danh học sinh trong các lớp được phân công
                    và theo dõi tình hình chuyên cần.
                </p>

            </div>
        </div>

    </a>
</div>

        {{-- =================================================
             THÔNG BÁO
        ================================================== --}}

        <div class="col-md-6 col-lg-4">

            <a
                href="#"
                class="feature-link"
            >

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="fa-solid fa-bell"></i>
                    </div>

                    <h5>
                        Thông báo
                    </h5>

                    <p class="text-muted mb-0">
                        Xem các thông báo từ nhà trường,
                        Ban Giám hiệu và quản trị viên.
                    </p>

                </div>

            </a>

        </div>


        {{-- =================================================
             ĐĂNG XUẤT
        ================================================== --}}

        <div class="col-md-6 col-lg-4">

            <div class="feature-card">

                <div class="feature-icon">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </div>

                <h5>
                    Đăng xuất
                </h5>

                <p class="text-muted">
                    Kết thúc phiên làm việc và đăng xuất
                    khỏi hệ thống.
                </p>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Đăng xuất
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>

</html>