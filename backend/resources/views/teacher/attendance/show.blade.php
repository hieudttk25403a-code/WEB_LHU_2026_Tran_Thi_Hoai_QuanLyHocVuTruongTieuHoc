@extends('layouts.app')

@section('title', 'Kết quả điểm danh')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-clipboard-check text-success me-2"></i>

                Kết quả điểm danh

            </h2>

            <p class="text-muted mb-0">

                Lớp:
                <strong>{{ $class->class_name }}</strong>

                <span class="mx-2">•</span>

                Năm học:
                <strong>{{ $schoolYear->name }}</strong>

            </p>

        </div>


        <a
            href="{{ route(
                'teacher.attendance.create',
                [
                    'class' => $class->id,
                    'school_year_id' => $schoolYear->id,
                    'date' => $date,
                ]
            ) }}"
            class="btn btn-primary"
        >

            <i class="fas fa-edit me-1"></i>

            Chỉnh sửa điểm danh

        </a>

    </div>


    {{-- SUCCESS --}}
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


    {{-- THÔNG TIN NGÀY --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Lớp
                    </div>

                    <div class="fw-bold">
                        {{ $class->class_name }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Năm học
                    </div>

                    <div class="fw-bold">
                        {{ $schoolYear->name }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Ngày điểm danh
                    </div>

                    <div class="fw-bold">

                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- BẢNG --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    <i class="fas fa-users me-2 text-success"></i>

                    Danh sách học sinh

                </h5>


                <span class="badge bg-light text-dark">

                    {{ $students->count() }} học sinh

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th
                                class="text-center"
                                width="70"
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
                            >
                                Trạng thái
                            </th>

                            <th>
                                Ghi chú
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($students as $index => $student)

                            @php

                                $attendance =
                                    $attendances->get(
                                        $student->id
                                    );

                            @endphp


                            <tr>

                                {{-- STT --}}
                                <td class="text-center">

                                    {{ $index + 1 }}

                                </td>


                                {{-- MÃ HS --}}
                                <td class="fw-semibold">

                                    {{ $student->student_code }}

                                </td>


                                {{-- HỌ TÊN --}}
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


                                {{-- TRẠNG THÁI --}}
                                <td class="text-center">

                                    @if(!$attendance)

                                        <span class="badge bg-secondary">

                                            Chưa điểm danh

                                        </span>

                                    @else

                                        @switch($attendance->status)

                                            @case('present')

                                                <span class="badge bg-success">

                                                    <i class="fas fa-check me-1"></i>

                                                    Có mặt

                                                </span>

                                                @break


                                            @case('absent')

                                                <span class="badge bg-danger">

                                                    <i class="fas fa-times me-1"></i>

                                                    Vắng

                                                </span>

                                                @break


                                            @case('late')

                                                <span class="badge bg-warning text-dark">

                                                    <i class="fas fa-clock me-1"></i>

                                                    Đi trễ

                                                </span>

                                                @break


                                            @case('excused')

                                                <span class="badge bg-info">

                                                    <i class="fas fa-file-circle-check me-1"></i>

                                                    Có phép

                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-secondary">

                                                    {{ $attendance->status }}

                                                </span>

                                        @endswitch

                                    @endif

                                </td>


                                {{-- GHI CHÚ --}}
                                <td>

                                    @if($attendance)

                                        {{ $attendance->note ?: '—' }}

                                    @else

                                        —

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="fas fa-user-slash text-muted fs-2 mb-3"
                                    ></i>

                                    <div class="text-muted">

                                        Lớp chưa có học sinh.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- NÚT QUAY LẠI --}}
    <div class="mt-4">

        <a
            href="{{ route(
                'teacher.attendance.index',
                [
                    'school_year_id' => $schoolYear->id
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Quay lại danh sách lớp

        </a>

    </div>

</div>

@endsection