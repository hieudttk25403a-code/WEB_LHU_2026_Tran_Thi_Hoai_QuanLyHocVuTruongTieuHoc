@extends('layouts.app')

@section('title', 'Điểm danh lớp ' . $class->class_name)

@section('content')

<div class="container-fluid py-4">

    {{-- =========================
        HEADER
    ========================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-user-check text-success me-2"></i>
                Điểm danh lớp {{ $class->class_name }}
            </h2>

            <p class="text-muted mb-0">
                Năm học:
                <strong>{{ $schoolYear->name }}</strong>
            </p>
        </div>

        <a
            href="{{ route(
                'teacher.attendance.index',
                ['school_year_id' => $schoolYear->id]
            ) }}"
            class="btn btn-outline-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Quay lại
        </a>

    </div>


    {{-- =========================
        THÔNG BÁO THÀNH CÔNG
    ========================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================
        THÔNG BÁO LỖI
    ========================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                <i class="fas fa-exclamation-circle me-1"></i>
                Có lỗi xảy ra:
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


    {{-- =========================
        CHỌN NGÀY ĐIỂM DANH
    ========================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <form
                method="GET"
                action="{{ route(
                    'teacher.attendance.create',
                    ['class' => $class->id]
                ) }}"
            >

                {{-- Năm học --}}
                <input
                    type="hidden"
                    name="school_year_id"
                    value="{{ $schoolYear->id }}"
                >

                <div class="row g-3 align-items-end">

                    {{-- Ngày --}}
                    <div class="col-md-4">

                        <label class="form-label fw-bold">

                            <i class="fas fa-calendar-day me-1"></i>

                            Ngày điểm danh

                        </label>

                        <input
                            type="date"
                            name="date"
                            value="{{ $date }}"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- Nút xem --}}
                    <div class="col-md-3">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="fas fa-calendar-day me-1"></i>

                            Xem ngày

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================
        FORM LƯU ĐIỂM DANH
    ========================== --}}
    <form
        method="POST"
        action="{{ route('teacher.attendance.store') }}"
    >

        @csrf


        {{-- =========================
            DỮ LIỆU CHUNG
        ========================== --}}

        <input
            type="hidden"
            name="class_id"
            value="{{ $class->id }}"
        >

        <input
            type="hidden"
            name="school_year_id"
            value="{{ $schoolYear->id }}"
        >

        <input
            type="hidden"
            name="attendance_date"
            value="{{ $date }}"
        >


        {{-- =========================
            CARD BẢNG ĐIỂM DANH
        ========================== --}}
        <div class="card border-0 shadow-sm rounded-4">

            {{-- HEADER CARD --}}
            <div class="card-header bg-white border-0 p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">

                            <i class="fas fa-users me-2 text-success"></i>

                            Danh sách học sinh

                        </h5>

                        <p class="text-muted mb-0">

                            Ngày:

                            <strong>
                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                            </strong>

                        </p>

                    </div>


                    {{-- NÚT LƯU --}}
                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="fas fa-save me-1"></i>

                        Lưu điểm danh

                    </button>

                </div>

            </div>


            {{-- =========================
                BODY
            ========================== --}}
            <div class="card-body p-0">

                @if($students->count())


                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">


                            {{-- =========================
                                HEADER BẢNG
                            ========================== --}}
                            <thead class="table-light">

                                <tr>

                                    <th
                                        class="text-center"
                                        width="60"
                                    >
                                        STT
                                    </th>


                                    <th>
                                        Mã HS
                                    </th>


                                    <th>
                                        Họ và tên
                                    </th>


                                    <th
                                        class="text-center"
                                        width="100"
                                    >
                                        Có mặt
                                    </th>


                                    <th
                                        class="text-center"
                                        width="100"
                                    >
                                        Vắng
                                    </th>


                                    <th
                                        class="text-center"
                                        width="100"
                                    >
                                        Đi trễ
                                    </th>


                                    <th
                                        class="text-center"
                                        width="100"
                                    >
                                        Có phép
                                    </th>


                                    <th>
                                        Ghi chú
                                    </th>

                                </tr>

                            </thead>


                            {{-- =========================
                                DANH SÁCH HỌC SINH
                            ========================== --}}
                            <tbody>

                                @foreach($students as $index => $student)


                                    @php

                                        $attendance = $attendances->get(
                                            $student->id
                                        );

                                        $status = optional(
                                            $attendance
                                        )->status ?? 'present';

                                    @endphp


                                    <tr>


                                        {{-- =================================
                                            QUAN TRỌNG:
                                            GỬI STUDENT_ID CHO CONTROLLER
                                        ================================== --}}
                                        <input
                                            type="hidden"
                                            name="attendance[{{ $student->id }}][student_id]"
                                            value="{{ $student->id }}"
                                        >


                                        {{-- STT --}}
                                        <td class="text-center">

                                            {{ $index + 1 }}

                                        </td>


                                        {{-- MÃ HỌC SINH --}}
                                        <td class="fw-semibold">

                                            {{ $student->student_code }}

                                        </td>


                                        {{-- HỌ VÀ TÊN --}}
                                        <td>

                                            <div class="fw-semibold">

                                                {{ $student->full_name }}

                                            </div>


                                            @if($student->gender)

                                                <small class="text-muted">

                                                    {{ $student->gender }}

                                                </small>

                                            @endif

                                        </td>


                                        {{-- =========================
                                            CÓ MẶT
                                        ========================== --}}
                                        <td class="text-center">

                                            <input
                                                type="radio"
                                                class="form-check-input attendance-radio"
                                                name="attendance[{{ $student->id }}][status]"
                                                value="present"

                                                @checked(
                                                    $status === 'present'
                                                )
                                            >

                                        </td>


                                        {{-- =========================
                                            VẮNG
                                        ========================== --}}
                                        <td class="text-center">

                                            <input
                                                type="radio"
                                                class="form-check-input attendance-radio"
                                                name="attendance[{{ $student->id }}][status]"
                                                value="absent"

                                                @checked(
                                                    $status === 'absent'
                                                )
                                            >

                                        </td>


                                        {{-- =========================
                                            ĐI TRỄ
                                        ========================== --}}
                                        <td class="text-center">

                                            <input
                                                type="radio"
                                                class="form-check-input attendance-radio"
                                                name="attendance[{{ $student->id }}][status]"
                                                value="late"

                                                @checked(
                                                    $status === 'late'
                                                )
                                            >

                                        </td>


                                        {{-- =========================
                                            CÓ PHÉP
                                        ========================== --}}
                                        <td class="text-center">

                                            <input
                                                type="radio"
                                                class="form-check-input attendance-radio"
                                                name="attendance[{{ $student->id }}][status]"
                                                value="excused"

                                                @checked(
                                                    $status === 'excused'
                                                )
                                            >

                                        </td>


                                        {{-- =========================
                                            GHI CHÚ
                                        ========================== --}}
                                        <td>

                                            <input
                                                type="text"
                                                class="form-control"
                                                name="attendance[{{ $student->id }}][note]"
                                                value="{{ optional($attendance)->note }}"
                                                placeholder="Ghi chú..."
                                            >

                                        </td>

                                    </tr>


                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =========================
                        NÚT LƯU CUỐI BẢNG
                    ========================== --}}
                    <div class="p-4 border-top">

                        <button
                            type="submit"
                            class="btn btn-success"
                        >

                            <i class="fas fa-save me-1"></i>

                            Lưu điểm danh

                        </button>

                    </div>


                @else


                    {{-- =========================
                        KHÔNG CÓ HỌC SINH
                    ========================== --}}
                    <div class="text-center py-5">

                        <i
                            class="fas fa-user-slash text-muted fs-1 mb-3"
                        ></i>

                        <h5 class="fw-bold">

                            Lớp chưa có học sinh

                        </h5>

                        <p class="text-muted">

                            Hiện tại lớp này chưa có học sinh.

                        </p>

                    </div>


                @endif

            </div>

        </div>

    </form>

</div>

@endsection