@extends('layouts.app')

@section('title','Dashboard Admin')

@section('content')

<h2 class="mb-4">

    Xin chào Admin 👋

</h2>

<div class="row">

    <div class="col-lg-3 col-md-6 mb-4">

        <div class="dashboard-card">

            <div class="card-icon bg-primary">

                <i class="fa-solid fa-user-graduate"></i>

            </div>

            <div>

                <h3>520</h3>

                <p>Học sinh</p>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6 mb-4">

        <div class="dashboard-card">

            <div class="card-icon bg-success">

                <i class="fa-solid fa-chalkboard-user"></i>

            </div>

            <div>

                <h3>35</h3>

                <p>Giáo viên</p>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6 mb-4">

        <div class="dashboard-card">

            <div class="card-icon bg-warning">

                <i class="fa-solid fa-school"></i>

            </div>

            <div>

                <h3>18</h3>

                <p>Lớp học</p>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6 mb-4">

        <div class="dashboard-card">

            <div class="card-icon bg-danger">

                <i class="fa-solid fa-book"></i>

            </div>

            <div>

                <h3>12</h3>

                <p>Môn học</p>

            </div>

        </div>

    </div>

</div>
<div class="row">

    <div class="col-lg-8 mb-4">

        <div class="dashboard-box">

            <h4 class="mb-4">

                Thống kê học sinh theo khối

            </h4>

            <canvas id="studentChart"></canvas>

        </div>

    </div>

    <div class="col-lg-4 mb-4">

        <div class="dashboard-box">

            <h4 class="mb-4">

                Thông báo mới

            </h4>

            <ul class="notification-list">

                <li>🔔 Có 5 học sinh nghỉ học.</li>

                <li>🔔 Giáo viên chưa nhập điểm.</li>

                <li>🔔 Sắp đến kỳ thi học kỳ.</li>

                <li>🔔 Có thông báo mới từ BGH.</li>

            </ul>

        </div>

    </div>

</div>
<div class="row">

    <!-- Recent Activity -->
    <div class="col-lg-8 mb-4">

        <div class="dashboard-box">

            <h4 class="mb-4">
                Hoạt động gần đây
            </h4>

            <div class="activity-item">
                <span class="activity-time">08:30</span>
                <div>
                    <strong>Admin</strong> đã thêm học sinh mới.
                </div>
            </div>

            <div class="activity-item">
                <span class="activity-time">09:15</span>
                <div>
                    <strong>Giáo viên</strong> đã nhập điểm lớp 5A.
                </div>
            </div>

            <div class="activity-item">
                <span class="activity-time">10:20</span>
                <div>
                    <strong>BGH</strong> đã tạo thông báo mới.
                </div>
            </div>

            <div class="activity-item">
                <span class="activity-time">11:00</span>
                <div>
                    <strong>Admin</strong> cập nhật thông tin giáo viên.
                </div>
            </div>

        </div>

    </div>

    <!-- Notification -->
    <div class="col-lg-4 mb-4">

        <div class="dashboard-box">

            <h4 class="mb-4">
                Thông báo
            </h4>

            <div class="notify-item">
                🔔 Có 5 học sinh nghỉ học.
            </div>

            <div class="notify-item">
                🔔 Có giáo viên chưa nhập điểm.
            </div>

            <div class="notify-item">
                🔔 Lớp 5A sắp thi học kỳ.
            </div>

            <div class="notify-item">
                🔔 Có thông báo mới từ Ban Giám Hiệu.
            </div>

        </div>

    </div>

</div>
@endsection