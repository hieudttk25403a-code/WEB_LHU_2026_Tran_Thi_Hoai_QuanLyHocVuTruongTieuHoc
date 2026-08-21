@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Tiêu đề --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            <i class="fas fa-tasks me-2"></i>
            Phân công giáo viên
        </h2>

        <p class="text-muted mb-0">
            Chọn loại phân công giáo viên cần thực hiện
        </p>
    </div>


    <div class="row g-4">

        {{-- PHÂN CÔNG CHỦ NHIỆM --}}
        <div class="col-md-6">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="rounded-circle bg-primary text-white
                                   d-flex align-items-center justify-content-center me-3"
                            style="width:55px;height:55px;"
                        >
                            <i class="fas fa-chalkboard-teacher fa-lg"></i>
                        </div>

                        <div>
                            <h4 class="fw-bold mb-1">
                                Giáo viên chủ nhiệm
                            </h4>

                            <p class="text-muted mb-0">
                                Phân công giáo viên chủ nhiệm cho lớp
                            </p>
                        </div>

                    </div>


                    <hr>


                    <p class="text-muted">
                        Dùng chức năng này để:
                    </p>

                    <ul class="text-muted">

                        <li>
                            Nhập mã giáo viên
                        </li>

                        <li>
                            Xác định giáo viên
                        </li>

                        <li>
                            Chọn lớp chủ nhiệm
                        </li>

                        <li>
                            Chọn môn giáo viên giảng dạy
                        </li>

                        <li>
                            Chọn năm học
                        </li>

                    </ul>


                    <div class="mt-4">

                        <a
                            href="{{ route('class-assignments.create') }}"
                            class="btn btn-primary w-100"
                        >

                            <i class="fas fa-user-tie me-2"></i>

                            Phân công giáo viên chủ nhiệm

                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- PHÂN CÔNG BỘ MÔN --}}
        <div class="col-md-6">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="rounded-circle bg-success text-white
                                   d-flex align-items-center justify-content-center me-3"
                            style="width:55px;height:55px;"
                        >
                            <i class="fas fa-book-open fa-lg"></i>
                        </div>

                        <div>

                            <h4 class="fw-bold mb-1">
                                Giáo viên bộ môn
                            </h4>

                            <p class="text-muted mb-0">
                                Phân công giáo viên dạy các môn học
                            </p>

                        </div>

                    </div>


                    <hr>


                    <p class="text-muted">
                        Dùng chức năng này để:
                    </p>


                    <ul class="text-muted">

                        <li>
                            Nhập mã giáo viên
                        </li>

                        <li>
                            Tự động xác định giáo viên
                        </li>

                        <li>
                            Tự động xác định môn chuyên nếu là giáo viên chuyên
                        </li>

                        <li>
                            Chọn lớp
                        </li>

                        <li>
                            Chọn môn học
                        </li>

                        <li>
                            Phân công theo tiết và ngày
                        </li>

                    </ul>


                    <div class="mt-4">

                        <a
                            href="{{ route('teacher-subject-assignments.create') }}"
                            class="btn btn-success w-100"
                        >

                            <i class="fas fa-book-reader me-2"></i>

                            Phân công giáo viên bộ môn

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Nút quay lại --}}

    <div class="mt-4">

        <a
            href="{{ route('teachers.index') }}"
            class="btn btn-outline-secondary"
        >

            <i class="fas fa-arrow-left me-2"></i>

            Quay lại danh sách giáo viên

        </a>

    </div>

</div>

@endsection