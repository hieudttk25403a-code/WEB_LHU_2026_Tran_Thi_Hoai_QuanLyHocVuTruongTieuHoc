<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hệ thống Quản lý Học vụ</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <i class="fa-solid fa-envelope"></i>
                    hocvu@tanlap3.edu.vn
                </div>

                <div>
                    <i class="fa-solid fa-phone"></i>
                    (0251) 3 888 999
                </div>

            </div>

        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">

        <div class="container">

            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="fa-solid fa-book-open-reader"></i>
                Học Vụ Tân Lập 3
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#menu">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link active" href="#">Trang chủ</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Giới thiệu</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Thông báo</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Liên hệ</a>
                    </li>

                    <li class="nav-item ms-3">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Đăng nhập
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>
<!-- Hero Banner -->
<section class="hero">

    <div class="overlay"></div>

    <div class="container hero-content">

        <h1>
            HỆ THỐNG QUẢN LÝ HỌC VỤ
        </h1>

        <h2>
            Trường Tiểu học Tân Lập 3
        </h2>

        <p>
            Quản lý học sinh, giáo viên, lớp học, điểm số và thông báo
            một cách hiện đại, nhanh chóng và hiệu quả.
        </p>

        <div class="mt-4">

            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-right-to-bracket"></i>
                Đăng nhập
            </a>

            <a href="#gioithieu" class="btn btn-outline-light btn-lg ms-2">
                Tìm hiểu thêm
            </a>

        </div>

    </div>

</section>
<!-- Giới thiệu -->
<section id="gioithieu" class="about-section">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <img src="{{ asset('images/school.jpg') }}"
                     class="img-fluid rounded shadow"
                     alt="Trường">

            </div>

            <div class="col-lg-6">

                <h5 class="section-title">
                    GIỚI THIỆU
                </h5>

                <h2>
                    Trường Tiểu học Tân Lập 3
                </h2>

                <p>

                    Hệ thống Quản lý Học vụ được xây dựng nhằm hỗ trợ
                    công tác quản lý học sinh, giáo viên, lớp học,
                    điểm số và thông báo một cách hiện đại, chính xác
                    và hiệu quả.

                </p>

                <div class="mt-4">

                    <p>

                        <i class="fa-solid fa-circle-check text-primary"></i>

                        Giáo viên tận tâm

                    </p>

                    <p>

                        <i class="fa-solid fa-circle-check text-primary"></i>

                        Quản lý học sinh khoa học

                    </p>

                    <p>

                        <i class="fa-solid fa-circle-check text-primary"></i>

                        Môi trường giáo dục thân thiện

                    </p>

                </div>

                <a href="#features"
                   class="btn btn-primary mt-3">

                    Xem chức năng

                </a>

            </div>

        </div>

    </div>

</section>
<!-- Chức năng -->
<section id="features" class="feature-section">

    <div class="container">

        <div class="text-center mb-5">

            <h5 class="section-title">
                CHỨC NĂNG NỔI BẬT
            </h5>

            <h2>
                Hệ thống quản lý học vụ hiện đại
            </h2>

            <p>
                Các chức năng hỗ trợ nhà trường quản lý nhanh chóng,
                chính xác và hiệu quả.
            </p>

        </div>

        <div class="row g-4">

            <!-- Card 1 -->
            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <i class="fa-solid fa-user-graduate"></i>

                    <h4>Quản lý học sinh</h4>

                    <p>
                        Thêm, sửa, xóa, tìm kiếm hồ sơ học sinh.
                    </p>

                </div>

            </div>

            <!-- Card 2 -->
            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <i class="fa-solid fa-chalkboard-user"></i>

                    <h4>Quản lý giáo viên</h4>

                    <p>
                        Quản lý thông tin và phân công giảng dạy.
                    </p>

                </div>

            </div>

            <!-- Card 3 -->
            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <i class="fa-solid fa-school"></i>

                    <h4>Quản lý lớp học</h4>

                    <p>
                        Quản lý lớp, sĩ số và giáo viên chủ nhiệm.
                    </p>

                </div>

            </div>

            <!-- Card 4 -->
            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <i class="fa-solid fa-pen"></i>

                    <h4>Quản lý điểm</h4>

                    <p>
                        Nhập điểm và tự động tính điểm trung bình.
                    </p>

                </div>

            </div>

            <!-- Card 5 -->
            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <i class="fa-solid fa-calendar-days"></i>

                    <h4>Thời khóa biểu</h4>

                    <p>
                        Theo dõi lịch học theo lớp và giáo viên.
                    </p>

                </div>

            </div>

            <!-- Card 6 -->
            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <i class="fa-solid fa-chart-column"></i>

                    <h4>Dashboard Analytics</h4>

                    <p>
                        Thống kê học sinh, giáo viên và kết quả học tập.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>
<!-- Tin tức -->
<section class="news-section">

    <div class="container">

        <div class="text-center mb-5">

            <h5 class="section-title">
                TIN TỨC NỔI BẬT
            </h5>

            <h2>
                Hoạt động mới nhất của nhà trường
            </h2>

        </div>

        <div class="row g-4">

            <!-- Tin 1 -->
            <div class="col-lg-4">

                <div class="news-card">

                    <img src="{{ asset('images/news1.jpg') }}" alt="">

                    <div class="news-content">

                        <span class="news-date">
                            <i class="fa-solid fa-calendar"></i>
                            05/09/2026
                        </span>

                        <h4>
                            Lễ khai giảng năm học mới
                        </h4>

                        <p>
                            Chào đón năm học mới với nhiều hoạt động ý nghĩa dành cho học sinh.
                        </p>

                        <a href="#">
                            Đọc thêm →
                        </a>

                    </div>

                </div>

            </div>

            <!-- Tin 2 -->
            <div class="col-lg-4">

                <div class="news-card">

                    <img src="{{ asset('images/news2.jpg') }}" alt="">

                    <div class="news-content">

                        <span class="news-date">
                            <i class="fa-solid fa-calendar"></i>
                            15/10/2026
                        </span>

                        <h4>
                            Hội thao cấp trường
                        </h4>

                        <p>
                            Các em học sinh tham gia nhiều hoạt động thể thao bổ ích.
                        </p>

                        <a href="#">
                            Đọc thêm →
                        </a>

                    </div>

                </div>

            </div>

            <!-- Tin 3 -->
            <div class="col-lg-4">

                <div class="news-card">

                    <img src="{{ asset('images/news3.jpg') }}" alt="">

                    <div class="news-content">

                        <span class="news-date">
                            <i class="fa-solid fa-calendar"></i>
                            20/11/2026
                        </span>

                        <h4>
                            Họp phụ huynh đầu năm
                        </h4>

                        <p>
                            Nhà trường phối hợp cùng phụ huynh nâng cao chất lượng giáo dục.
                        </p>

                        <a href="#">
                            Đọc thêm →
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
<!-- Thống kê -->
<section class="counter-section">

    <div class="container">

        <div class="row text-center">

            <div class="col-lg-3 col-6 mb-4">

                <div class="counter-box">

                    <i class="fa-solid fa-user-graduate"></i>

                    <h2 class="counter" data-target="520">0</h2>

                    <p>Học sinh</p>

                </div>

            </div>

            <div class="col-lg-3 col-6 mb-4">

                <div class="counter-box">

                    <i class="fa-solid fa-chalkboard-user"></i>

                    <h2 class="counter" data-target="35">0</h2>

                    <p>Giáo viên</p>

                </div>

            </div>

            <div class="col-lg-3 col-6 mb-4">

                <div class="counter-box">

                    <i class="fa-solid fa-school"></i>

                    <h2 class="counter" data-target="18">0</h2>

                    <p>Lớp học</p>

                </div>

            </div>

            <div class="col-lg-3 col-6 mb-4">

                <div class="counter-box">

                    <i class="fa-solid fa-book"></i>

                    <h2 class="counter" data-target="12">0</h2>

                    <p>Môn học</p>

                </div>

            </div>

        </div>

    </div>

</section>
<!-- Liên hệ -->
<section class="contact-section">

    <div class="container">

        <div class="text-center mb-5">

            <h5 class="section-title">
                LIÊN HỆ
            </h5>

            <h2>
                Thông tin liên hệ
            </h2>

        </div>

        <div class="row align-items-center">

            <div class="col-lg-5">

                <div class="contact-card">

                    <h4 class="mb-4">
                        Trường Tiểu học Tân Lập 3
                    </h4>

                    <p>
                        <i class="fa-solid fa-location-dot"></i>
                        Địa chỉ: Xã Tân Lập, Tỉnh Lâm Đồng  
                    </p>

                    <p>
                        <i class="fa-solid fa-phone"></i>
                        Điện thoại: (0251) 3 888 999
                    </p>

                    <p>
                        <i class="fa-solid fa-envelope"></i>
                        Email: hocvu@tanlap3.edu.vn
                    </p>

                    <p>
                        <i class="fa-solid fa-clock"></i>
                        Thứ 2 - Thứ 6
                        (07:00 - 17:00)
                    </p>

                </div>

            </div>

            <div class="col-lg-7">

                <iframe
                    src="https://www.google.com/maps?q=Xã+Tân+Lập,+Lâm+Đồng&output=embed"
                    width="100%"
                    height="380"
                    style="border:0;border-radius:15px;"
                    loading="lazy">
                </iframe>

            </div>

        </div>

    </div>

</section>
<!-- Footer -->
<footer class="footer">

    <div class="container">

        <div class="row">

            <div class="col-lg-4">

                <h4>
                    Trường Tiểu học Tân Lập 3
                </h4>

                <p>
                    Hệ thống Quản lý Học vụ hỗ trợ quản lý học sinh,
                    giáo viên, điểm số và các hoạt động của nhà trường.
                </p>

            </div>

            <div class="col-lg-4">

                <h5>
                    Liên kết
                </h5>

                <ul class="footer-menu">

                    <li><a href="#">Trang chủ</a></li>

                    <li><a href="#">Giới thiệu</a></li>

                    <li><a href="#">Thông báo</a></li>

                    <li><a href="{{ route('login') }}">Đăng nhập</a></li>

                </ul>

            </div>

            <div class="col-lg-4">

                <h5>
                    Kết nối
                </h5>

                <div class="social">

                    <a href="#">
                        <i class="fab fa-facebook"></i>
                    </a>

                    <a href="#">
                        <i class="fab fa-youtube"></i>
                    </a>

                    <a href="#">
                        <i class="fab fa-tiktok"></i>
                    </a>

                </div>

            </div>

        </div>

        <hr>

        <div class="text-center">

            © 2026 Hệ thống Quản lý Học vụ - Trường Tiểu học Tân Lập 3

        </div>

    </div>

</footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Home JS -->
    <script src="{{ asset('js/home.js') }}"></script>
</body>

</html>