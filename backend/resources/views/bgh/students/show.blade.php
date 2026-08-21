@extends('layouts.app')

@section('title', 'Chi tiết học sinh')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
         NÚT QUAY LẠI
    ====================================================== --}}

    <div class="mb-3">

        <a
            href="{{ route('bgh.students.index') }}"
            class="btn btn-outline-secondary"
        >

            <i class="fa-solid fa-arrow-left me-1"></i>

            Quay lại danh sách

        </a>

    </div>


    {{-- =====================================================
         THÔNG TIN CƠ BẢN
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="fa-solid fa-user-graduate me-2"></i>

                Thông tin học sinh

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- MÃ HS --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Mã học sinh
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $student->student_code }}

                    </div>

                </div>


                {{-- HỌ TÊN --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Họ và tên
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $student->full_name }}

                    </div>

                </div>


                {{-- GIỚI TÍNH --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Giới tính
                    </div>

                    <div class="fw-semibold">

                        {{ $student->gender ?? '—' }}

                    </div>

                </div>


                {{-- NGÀY SINH --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Ngày sinh
                    </div>

                    <div class="fw-semibold">

                        {{
                            $student->date_of_birth
                                ? \Carbon\Carbon::parse(
                                    $student->date_of_birth
                                )->format('d/m/Y')
                                : '—'
                        }}

                    </div>

                </div>


                {{-- LỚP --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Lớp hiện tại
                    </div>

                    <div class="fw-semibold">

                        @if($student->schoolClass)

                            {{ $student->schoolClass->class_name }}

                        @else

                            Chưa xếp lớp

                        @endif

                    </div>

                </div>


                {{-- TRẠNG THÁI --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Trạng thái
                    </div>

                    <div>

                        @if($student->status === 'Đang học')

                            <span class="badge bg-success">
                                Đang học
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $student->status }}
                            </span>

                        @endif

                    </div>

                </div>


                {{-- ĐỊA CHỈ --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Địa chỉ
                    </div>

                    <div class="fw-semibold">

                        {{ $student->address ?? '—' }}

                    </div>

                </div>


                {{-- EMAIL --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Email
                    </div>

                    <div class="fw-semibold">

                        {{ $student->email ?? '—' }}

                    </div>

                </div>


                {{-- ĐIỆN THOẠI --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Số điện thoại
                    </div>

                    <div class="fw-semibold">

                        {{ $student->phone ?? '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         LỊCH SỬ LỚP
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fa-solid fa-clock-rotate-left me-2"></i>

                Lịch sử lớp học

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Năm học
                            </th>

                            <th>
                                Lớp
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th>
                                Ngày bắt đầu
                            </th>

                            <th>
                                Ngày kết thúc
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $student->classHistories
                            as $history
                        )

                            <tr>

                                <td>

                                    {{
                                        $history->schoolYear
                                            ? $history->schoolYear->name
                                            : '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $history->schoolClass
                                            ? $history->schoolClass->class_name
                                            : '—'
                                    }}

                                </td>


                                <td>

                                    {{ $history->status ?? '—' }}

                                </td>


                                <td>

                                    {{
                                        $history->start_date
                                            ? \Carbon\Carbon::parse(
                                                $history->start_date
                                            )->format('d/m/Y')
                                            : '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $history->end_date
                                            ? \Carbon\Carbon::parse(
                                                $history->end_date
                                            )->format('d/m/Y')
                                            : '—'
                                    }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-4"
                                >

                                    Chưa có lịch sử lớp học.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =====================================================
         THÔNG TIN PHỤ HUYNH
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fa-solid fa-people-roof me-2"></i>

                Thông tin phụ huynh

            </h5>

        </div>


        <div class="card-body">

            @if($student->parents->count())

                <div class="row g-3">

                    @foreach($student->parents as $parent)

                        <div class="col-md-6">

                            <div class="border rounded p-3">

                                <div class="fw-bold">

                                    {{ $parent->full_name }}

                                </div>

                                <div class="text-muted">

                                    Quan hệ:
                                    {{ $parent->relationship ?? '—' }}

                                </div>

                                <div class="text-muted">

                                    Điện thoại:
                                    {{ $parent->phone ?? '—' }}

                                </div>

                                <div class="text-muted">

                                    Email:
                                    {{ $parent->email ?? '—' }}

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-muted">

                    Chưa có thông tin phụ huynh.

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         ĐIỂM SỐ
    ====================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-success text-white">

            <h5 class="mb-0">

                <i class="fa-solid fa-chart-line me-2"></i>

                Kết quả điểm số

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Môn học
                            </th>

                            <th>
                                Năm học
                            </th>

                            <th class="text-center">
                                Miệng
                            </th>

                            <th class="text-center">
                                15 phút
                            </th>

                            <th class="text-center">
                                Giữa kỳ
                            </th>

                            <th class="text-center">
                                Cuối kỳ
                            </th>

                            <th class="text-center">
                                TB
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $student->scores
                            as $score
                        )

                            <tr>

                                <td class="fw-semibold">

                                    {{
                                        $score->subject
                                            ? $score->subject->subject_name
                                            : '—'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $score->schoolYear
                                            ? $score->schoolYear->name
                                            : '—'
                                    }}

                                </td>


                                <td class="text-center">

                                    {{ $score->oral_score ?? '—' }}

                                </td>


                                <td class="text-center">

                                    {{
                                        $score->fifteen_minute_score
                                            ?? '—'
                                    }}

                                </td>


                                <td class="text-center">

                                    {{ $score->midterm_score ?? '—' }}

                                </td>


                                <td class="text-center">

                                    {{ $score->final_score ?? '—' }}

                                </td>


                                <td class="text-center fw-bold">

                                    {{ $score->average_score ?? '—' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center text-muted py-4"
                                >

                                    Chưa có dữ liệu điểm.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


</div>

@endsection