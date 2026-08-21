<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard Ban Giám Hiệu</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            background: #f4f7fb;
            font-family: 'Poppins', sans-serif;
            color: #1f2937;
        }

        /*
        |--------------------------------------------------------------------------
        | SIDEBAR
        |--------------------------------------------------------------------------
        */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;

            width: 250px;

            background: #123b8f;

            color: white;

            padding-top: 20px;

            overflow-y: auto;

            z-index: 1000;
        }

        .logo {
            height: 70px;

            display: flex;
            align-items: center;

            padding: 0 25px;

            font-size: 22px;
            font-weight: 700;

            border-bottom: 1px solid
                rgba(255,255,255,0.12);
        }

        .logo i {
            margin-right: 12px;
        }

        .sidebar-menu {
            list-style: none;

            padding: 15px 0;

            margin: 0;
        }

        .sidebar-menu li {
            margin: 4px 12px;
        }

        .sidebar-menu a {
            display: flex;

            align-items: center;

            gap: 13px;

            padding: 13px 15px;

            color: white;

            text-decoration: none;

            border-radius: 8px;

            font-size: 14px;

            transition: 0.2s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.16);
        }

        .sidebar-menu i {
            width: 22px;

            text-align: center;
        }

        .sidebar-footer {
            position: absolute;

            left: 15px;
            right: 15px;
            bottom: 20px;
        }

        .logout-btn {
            width: 100%;

            border: none;

            border-radius: 8px;

            padding: 11px;

            background: rgba(255,255,255,0.12);

            color: white;

            font-size: 14px;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        */

        .main-content {
            margin-left: 250px;

            min-height: 100vh;
        }

        /*
        |--------------------------------------------------------------------------
        | TOPBAR
        |--------------------------------------------------------------------------
        */

        .topbar {
            height: 70px;

            background: white;

            border-bottom: 1px solid #e5e7eb;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 30px;
        }

        .topbar-title {
            font-size: 20px;

            font-weight: 600;
        }

        .user-info {
            display: flex;

            align-items: center;

            gap: 12px;
        }

        .avatar {
            width: 42px;
            height: 42px;

            border-radius: 50%;

            background: #2563eb;

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 600;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

        .content {
            padding: 30px;
        }

        .welcome-card {
            background: white;

            border-radius: 14px;

            padding: 25px;

            box-shadow:
                0 2px 10px
                rgba(0,0,0,0.05);

            margin-bottom: 25px;
        }

        .welcome-card h2 {
            font-weight: 700;
        }

        .role-badge {
            display: inline-block;

            padding: 7px 14px;

            border-radius: 20px;

            background: #e0ecff;

            color: #1d4ed8;

            font-size: 13px;

            font-weight: 600;
        }

        /*
        |--------------------------------------------------------------------------
        | STAT CARDS
        |--------------------------------------------------------------------------
        */

        .stat-card {
            background: white;

            border-radius: 14px;

            padding: 22px;

            box-shadow:
                0 2px 10px
                rgba(0,0,0,0.05);

            height: 100%;
        }

        .stat-icon {
            width: 50px;
            height: 50px;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 21px;

            margin-bottom: 15px;
        }

        .stat-card h3 {
            font-size: 28px;

            margin-bottom: 3px;

            font-weight: 700;
        }

        .stat-card p {
            color: #6b7280;

            margin: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | FEATURE CARDS
        |--------------------------------------------------------------------------
        */

        .feature-card {
            background: white;

            border-radius: 14px;

            padding: 22px;

            height: 100%;

            box-shadow:
                0 2px 10px
                rgba(0,0,0,0.05);

            transition: 0.2s;
        }

        .feature-card:hover {
            transform: translateY(-3px);

            box-shadow:
                0 6px 18px
                rgba(0,0,0,0.08);
        }

        .feature-icon {
            width: 48px;
            height: 48px;

            border-radius: 12px;

            background: #eff6ff;

            color: #2563eb;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 20px;

            margin-bottom: 15px;
        }

        .feature-card h5 {
            font-weight: 600;
        }

        .feature-card p {
            color: #6b7280;

            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 900px) {

            .sidebar {
                width: 210px;
            }

            .main-content {
                margin-left: 210px;
            }

        }

        @media (max-width: 700px) {

            .sidebar {
                position: relative;

                width: 100%;

                height: auto;
            }

            .sidebar-footer {
                position: relative;

                left: auto;
                right: auto;
                bottom: auto;

                margin: 15px;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 0 15px;
            }

            .content {
                padding: 15px;
            }

        }

    </style>

</head>


<body>


{{-- =========================================================
     SIDEBAR BGH
========================================================= --}}

<div class="sidebar">

    <div class="logo">

        <i class="fa-solid fa-school"></i>

        <span>Tân Lập 3</span>

    </div>


    <ul class="sidebar-menu">

        {{-- DASHBOARD --}}

        <li>

            <a
                href="{{ route('bgh.dashboard') }}"
                class="active"
            >

                <i class="fa-solid fa-house"></i>

                <span>Dashboard</span>

            </a>

        </li>


        {{-- HỌC SINH --}}

        <li>

            <a href="{{ route('bgh.students.index') }}">

                <i class="fa-solid fa-user-graduate"></i>

                <span>Học sinh</span>

            </a>

        </li>


        {{-- GIÁO VIÊN --}}

        <li>

            <a href="{{ route('bgh.teachers.index') }}">

                <i class="fa-solid fa-chalkboard-user"></i>

                <span>Giáo viên</span>

            </a>

        </li>


        {{-- LỚP HỌC --}}

        <li>

            <a href="{{ route('bgh.classes.index') }}">

                <i class="fa-solid fa-school"></i>

                <span>Lớp học</span>

            </a>

        </li>




    {{-- ĐĂNG XUẤT --}}

    <div class="sidebar-footer">

        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf

            <button
                type="submit"
                class="logout-btn"
            >

                <i class="fa-solid fa-right-from-bracket me-2"></i>

                Đăng xuất

            </button>

        </form>

    </div>

</div>



{{-- =========================================================
     MAIN CONTENT
========================================================= --}}

<div class="main-content">


    {{-- TOPBAR --}}

    <div class="topbar">

        <div class="topbar-title">

            Dashboard Ban Giám Hiệu

        </div>


        <div class="user-info">

            <div class="text-end">

                <div class="fw-semibold">

                    {{ auth()->user()->name }}

                </div>

                <small class="text-muted">

                    Ban Giám Hiệu

                </small>

            </div>


            <div class="avatar">

                {{ strtoupper(
                    substr(
                        auth()->user()->name,
                        0,
                        1
                    )
                ) }}

            </div>

        </div>

    </div>



    {{-- CONTENT --}}

    <div class="content">


        {{-- WELCOME --}}

        <div class="welcome-card">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h2 class="mb-2">

                        Xin chào,
                        {{ auth()->user()->name }}! 👋

                    </h2>

                    <p class="text-muted mb-3">

                        Chào mừng Ban Giám Hiệu đến với
                        hệ thống Quản lý học vụ Trường Tiểu học Tân Lập 3.

                    </p>

                    <span class="role-badge">

                        <i class="fa-solid fa-user-tie me-1"></i>

                        Ban Giám Hiệu

                    </span>

                </div>


                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <i
                        class="fa-solid fa-building-columns"
                        style="
                            font-size:80px;
                            color:#2563eb;
                            opacity:.15;
                        "
                    ></i>

                </div>

            </div>

        </div>



        {{-- =================================================
             THỐNG KÊ TỔNG QUAN
        ================================================== --}}

        <h5 class="mb-3">

            Tổng quan nhà trường

        </h5>


        <div class="row g-4 mb-4">


            {{-- HỌC SINH --}}

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div
                        class="stat-icon"
                        style="
                            background:#e0f2fe;
                            color:#0284c7;
                        "
                    >

                        <i class="fa-solid fa-user-graduate"></i>

                    </div>

                    <h3>

                        {{ \App\Models\Student::count() }}

                    </h3>

                    <p>

                        Tổng số học sinh

                    </p>

                </div>

            </div>


            {{-- GIÁO VIÊN --}}

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div
                        class="stat-icon"
                        style="
                            background:#dcfce7;
                            color:#16a34a;
                        "
                    >

                        <i class="fa-solid fa-chalkboard-user"></i>

                    </div>

                    <h3>

                        {{ \App\Models\Teacher::count() }}

                    </h3>

                    <p>

                        Tổng số giáo viên

                    </p>

                </div>

            </div>


            {{-- LỚP --}}

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div
                        class="stat-icon"
                        style="
                            background:#fef3c7;
                            color:#d97706;
                        "
                    >

                        <i class="fa-solid fa-school"></i>

                    </div>

                    <h3>

                        {{ \App\Models\SchoolClass::count() }}

                    </h3>

                    <p>

                        Tổng số lớp học

                    </p>

                </div>

            </div>


            {{-- MÔN --}}

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div
                        class="stat-icon"
                        style="
                            background:#ede9fe;
                            color:#7c3aed;
                        "
                    >

                        <i class="fa-solid fa-book"></i>

                    </div>

                    <h3>

                        {{ \App\Models\Subject::count() }}

                    </h3>

                    <p>

                        Tổng số môn học

                    </p>

                </div>

            </div>

        </div>



        {{-- =================================================
             CHỨC NĂNG BGH
        ================================================== --}}

        <h5 class="mb-3">

            Chức năng quản lý

        </h5>


        <div class="row g-4">


            {{-- HỌC SINH --}}

            <div class="col-xl-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">

                        <i class="fa-solid fa-user-graduate"></i>

                    </div>

                    <h5>

                        Xem danh sách học sinh

                    </h5>

                    <p>

                        Theo dõi danh sách học sinh
                        toàn trường, tìm kiếm theo tên
                        hoặc lớp và xem thông tin chi tiết.

                    </p>

                    <span class="text-primary fw-semibold">

                        Xem danh sách →

                    </span>

                </div>

            </div>


            {{-- GIÁO VIÊN --}}

            <div class="col-xl-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">

                        <i class="fa-solid fa-chalkboard-user"></i>

                    </div>

                    <h5>

                        Xem danh sách giáo viên

                    </h5>

                    <p>

                        Theo dõi giáo viên đang công tác,
                        tìm kiếm và xem thông tin chi tiết.

                    </p>

                    <span class="text-primary fw-semibold">

                        Xem danh sách →

                    </span>

                </div>

            </div>


            {{-- LỚP HỌC --}}

            <div class="col-xl-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">

                        <i class="fa-solid fa-school"></i>

                    </div>

                    <h5>

                        Xem danh sách lớp học

                    </h5>

                    <p>

                        Theo dõi sĩ số, giáo viên chủ nhiệm
                        và thông tin các lớp học.

                    </p>

                    <span class="text-primary fw-semibold">

                        Xem danh sách →

                    </span>

                </div>

            </div>


            {{-- HỌC TẬP --}}

            <div class="col-xl-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">

                        <i class="fa-solid fa-chart-line"></i>

                    </div>

                    <h5>

                        Kết quả học tập

                    </h5>

                    <p>

                        Theo dõi điểm số, điểm trung bình
                        và kết quả học tập của học sinh.

                    </p>

                    <span class="text-primary fw-semibold">

                        Xem kết quả →

                    </span>

                </div>

            </div>


            {{-- RÈN LUYỆN --}}

            <div class="col-xl-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">

                        <i class="fa-solid fa-award"></i>

                    </div>

                    <h5>

                        Kết quả rèn luyện

                    </h5>

                    <p>

                        Theo dõi hạnh kiểm, nhận xét
                        và kết quả rèn luyện của học sinh.

                    </p>

                    <span class="text-primary fw-semibold">

                        Xem kết quả →

                    </span>

                </div>

            </div>


            {{-- SĨ SỐ --}}

            <div class="col-xl-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">

                        <i class="fa-solid fa-user-check"></i>

                    </div>

                    <h5>

                        Theo dõi sĩ số

                    </h5>

                    <p>

                        Theo dõi số lượng học sinh đang học,
                        nghỉ học và tình hình chuyên cần.

                    </p>

                    <span class="text-primary fw-semibold">

                        Theo dõi →

                    </span>

                </div>

            </div>


            {{-- THỜI KHÓA BIỂU --}}

            <div class="col-xl-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">

                        <i class="fa-solid fa-calendar-days"></i>

                    </div>

                    <h5>

                        Thời khóa biểu

                    </h5>

                    <p>

                        Xem thời khóa biểu của các lớp
                        và giáo viên trong trường.

                    </p>

                    <span class="text-primary fw-semibold">

                        Xem thời khóa biểu →

                    </span>

                </div>

            </div>


            {{-- THỐNG KÊ --}}

            <div class="col-xl-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">

                        <i class="fa-solid fa-chart-column"></i>

                    </div>

                    <h5>

                        Thống kê

                    </h5>

                    <p>

                        Theo dõi thống kê học sinh,
                        giáo viên, kết quả học tập
                        và tỷ lệ lên lớp.

                    </p>

                    <span class="text-primary fw-semibold">

                        Xem thống kê →

                    </span>

                </div>

            </div>


            {{-- CÁ NHÂN --}}

            <div class="col-xl-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon">

                        <i class="fa-solid fa-user-gear"></i>

                    </div>

                    <h5>

                        Thông tin cá nhân

                    </h5>

                    <p>

                        Cập nhật thông tin cá nhân
                        và thay đổi mật khẩu tài khoản.

                    </p>

                    <span class="text-primary fw-semibold">

                        Cập nhật →

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>