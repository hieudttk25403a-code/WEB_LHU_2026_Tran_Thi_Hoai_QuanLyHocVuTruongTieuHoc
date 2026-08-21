<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Lịch giảng dạy</title>

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

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .page-title {
            color: #198754;
            font-weight: 600;
        }

        .table thead th {
            background-color: #198754;
            color: white;
            vertical-align: middle;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .day-badge {
            display: inline-block;
            min-width: 90px;
            padding: 6px 12px;
            border-radius: 20px;
            background-color: #e8f5e9;
            color: #198754;
            font-weight: 600;
            text-align: center;
        }

        .empty-box {
            padding: 50px 20px;
            text-align: center;
            color: #6c757d;
        }

        .subject-name {
            font-weight: 600;
        }

        .class-name {
            font-weight: 600;
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

<div class="container py-4">

    <div class="content-card">


        {{-- =================================================
             HEADER
        ================================================== --}}

        <div
            class="d-flex justify-content-between align-items-center mb-4"
        >

            <div>

                <h3 class="page-title mb-1">

                    <i class="fa-solid fa-chalkboard-user me-2"></i>

                    Lịch giảng dạy

                </h3>

                <p class="text-muted mb-0">

                    Danh sách các lớp và môn học được phân công.

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
             THÔNG TIN GIÁO VIÊN
        ================================================== --}}

        <div class="alert alert-success">

            <i class="fa-solid fa-user-tie me-2"></i>

            <strong>Giáo viên:</strong>

            {{ $teacher->full_name }}

            <span class="ms-3">

                <strong>Mã:</strong>

                {{ $teacher->teacher_code }}

            </span>

        </div>


        {{-- =================================================
             THÔNG BÁO
        ================================================== --}}

        @if(session('error'))

            <div class="alert alert-danger">

                {{ session('error') }}

            </div>

        @endif


        {{-- =================================================
             DANH SÁCH LỊCH GIẢNG DẠY
        ================================================== --}}

        @if($assignments->count() > 0)

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th style="width: 80px;">
                                STT
                            </th>

                            <th>
                                Thứ
                            </th>

                            <th>
                                Lớp
                            </th>

                            <th>
                                Môn học
                            </th>

                            <th>
                                Năm học
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($assignments as $index => $assignment)

                            <tr>

                                {{-- STT --}}

                                <td class="text-center">

                                    {{ $index + 1 }}

                                </td>


                                {{-- THỨ --}}

                                <td>

                                    @php

                                        $days = [
                                            1 => 'Thứ 2',
                                            2 => 'Thứ 3',
                                            3 => 'Thứ 4',
                                            4 => 'Thứ 5',
                                            5 => 'Thứ 6',
                                            6 => 'Thứ 7',
                                            7 => 'Chủ nhật',
                                        ];

                                        $day = $days[
                                            (int) $assignment->day_of_week
                                        ] ?? $assignment->day_of_week;

                                    @endphp

                                    <span class="day-badge">

                                        {{ $day }}

                                    </span>

                                </td>


                                {{-- LỚP --}}

                                <td>

                                    @if($assignment->schoolClass)

                                        <span class="class-name">

                                            {{ $assignment->schoolClass->class_name }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            Chưa xác định

                                        </span>

                                    @endif

                                </td>


                                {{-- MÔN HỌC --}}

                                <td>

                                    @if($assignment->subject)

                                        <span class="subject-name">

                                            {{ $assignment->subject->subject_name }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            Chưa xác định

                                        </span>

                                    @endif

                                </td>


                                {{-- NĂM HỌC --}}

                                <td>

                                    @if($assignment->schoolYear)

                                        {{ $assignment->schoolYear->name }}

                                    @else

                                        <span class="text-muted">

                                            Chưa xác định

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-box">

                <i
                    class="fa-solid fa-calendar-xmark mb-3"
                    style="font-size: 45px;"
                ></i>

                <h5>
                    Chưa có lịch giảng dạy
                </h5>

                <p class="mb-0">

                    Hiện tại bạn chưa được phân công
                    lớp học và môn học.

                </p>

            </div>

        @endif


    </div>

</div>


</body>

</html>