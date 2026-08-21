@extends('layouts.app')

@section('title', 'Quản lý điểm số')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         TIÊU ĐỀ
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fa-solid fa-chart-line me-2 text-primary"></i>
                Quản lý điểm số
            </h2>

            <p class="text-muted mb-0">
                Nhập và quản lý điểm các lớp, môn học được phân công
            </p>
        </div>

    </div>


    {{-- =========================================================
         THÔNG BÁO
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fa-solid fa-circle-check me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fa-solid fa-triangle-exclamation me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                Có lỗi xảy ra:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         THÔNG TIN GIÁO VIÊN
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Giáo viên
                    </div>

                    <div class="fw-bold fs-5">
                        {{ $teacher->full_name }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Mã giáo viên
                    </div>

                    <div class="fw-bold fs-5">
                        {{ $teacher->teacher_code }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Năm học
                    </div>

                    <div class="fw-bold fs-5">

                        @if(isset($schoolYear) && $schoolYear)

                            {{ $schoolYear->name }}

                        @else

                            Chưa xác định

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         CHỌN PHÂN CÔNG
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="fa-solid fa-filter me-2"></i>

                Chọn lớp và môn học

            </h5>

        </div>


        <div class="card-body">

            @if(isset($assignments) && $assignments->count() > 0)

                <form method="GET"
                      action="{{ route('teacher.scores.index') }}">

                    <div class="row align-items-end">

                        <div class="col-md-9">

                            <label class="form-label fw-semibold">
                                Phân công giảng dạy
                            </label>

                            <select name="assignment_id"
                                    class="form-select"
                                    onchange="this.form.submit()">

                                @foreach($assignments as $assignment)

                                    <option
                                        value="{{ $assignment->id }}"

                                        {{ $selectedAssignment
                                            && $selectedAssignment->id == $assignment->id
                                                ? 'selected'
                                                : '' }}>

                                        {{ $assignment->schoolClass->class_name ?? 'Lớp' }}

                                        -
                                        
                                        {{ $assignment->subject->subject_name ?? 'Môn học' }}

                                        @if(!empty($assignment->day_of_week))

                                            -
                                            Thứ {{ $assignment->day_of_week }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-3">

                            <button type="submit"
                                    class="btn btn-primary w-100">

                                <i class="fa-solid fa-magnifying-glass me-1"></i>

                                Xem danh sách

                            </button>

                        </div>

                    </div>

                </form>

            @else

                <div class="alert alert-warning mb-0">

                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                    Bạn chưa được phân công lớp và môn học nào.

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         THÔNG TIN PHÂN CÔNG ĐANG CHỌN
    ========================================================== --}}

    @if(isset($selectedAssignment) && $selectedAssignment)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Lớp
                        </div>

                        <div class="fw-bold fs-5">
                            {{ $selectedAssignment->schoolClass->class_name ?? '—' }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Môn học
                        </div>

                        <div class="fw-bold fs-5">

                            {{ $selectedAssignment->subject->subject_name ?? '—' }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Lịch giảng dạy
                        </div>

                        <div class="fw-bold fs-5">

                            @if($selectedAssignment->day_of_week)

                                Thứ {{ $selectedAssignment->day_of_week }}

                            @else

                                Chưa phân công thứ

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             BẢNG NHẬP ĐIỂM
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-5">

            <div class="card-header bg-success text-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1">

                            <i class="fa-solid fa-pen-to-square me-2"></i>

                            Nhập điểm

                        </h5>

                        <small>

                            {{ $selectedAssignment->subject->subject_name ?? '' }}

                            -
                            
                            {{ $selectedAssignment->schoolClass->class_name ?? '' }}

                        </small>

                    </div>


                    <span class="badge bg-light text-dark">

                        {{ $students->count() }} học sinh

                    </span>

                </div>

            </div>


            <form method="POST"
                  action="{{ route('teacher.scores.store') }}">

                @csrf

                <input
                    type="hidden"
                    name="assignment_id"
                    value="{{ $selectedAssignment->id }}">


                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light text-center">

                            <tr>

                                <th style="width: 60px;">
                                    STT
                                </th>

                                <th style="min-width: 220px;">
                                    Học sinh
                                </th>

                                <th style="width: 120px;">
                                    Miệng
                                </th>

                                <th style="width: 120px;">
                                    15 phút
                                </th>

                                <th style="width: 120px;">
                                    Giữa kỳ
                                </th>

                                <th style="width: 120px;">
                                    Cuối kỳ
                                </th>

                                <th style="width: 110px;">
                                    TB môn
                                </th>

                                <th style="width: 180px;">
                                    Trạng thái sửa
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($students as $index => $student)

                                @php

                                    $score =
                                        $scores->get($student->id);

                                    $editCount =
                                        $score
                                            ? $score->editHistories()->count()
                                            : 0;

                                    $remaining =
                                        max(
                                            0,
                                            3 - $editCount
                                        );

                                    $locked =
                                        $editCount >= 3;

                                @endphp


                                <tr>

                                    {{-- STT --}}

                                    <td class="text-center">

                                        {{ $index + 1 }}

                                    </td>


                                    {{-- HỌC SINH --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $student->full_name }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $student->student_code }}

                                        </small>

                                    </td>


                                    {{-- MIỆNG --}}

                                    <td>

                                        <input
                                            type="number"
                                            name="scores[{{ $student->id }}][oral_score]"
                                            class="form-control text-center score-input"
                                            min="0"
                                            max="10"
                                            step="0.01"

                                            value="{{ old(
                                                'scores.'.$student->id.'.oral_score',
                                                $score->oral_score ?? ''
                                            ) }}"

                                            {{ $locked ? 'readonly' : '' }}

                                            placeholder="—">

                                    </td>


                                    {{-- 15 PHÚT --}}

                                    <td>

                                        <input
                                            type="number"
                                            name="scores[{{ $student->id }}][fifteen_minute_score]"
                                            class="form-control text-center score-input"
                                            min="0"
                                            max="10"
                                            step="0.01"

                                            value="{{ old(
                                                'scores.'.$student->id.'.fifteen_minute_score',
                                                $score->fifteen_minute_score ?? ''
                                            ) }}"

                                            {{ $locked ? 'readonly' : '' }}

                                            placeholder="—">

                                    </td>


                                    {{-- GIỮA KỲ --}}

                                    <td>

                                        <input
                                            type="number"
                                            name="scores[{{ $student->id }}][midterm_score]"
                                            class="form-control text-center score-input"
                                            min="0"
                                            max="10"
                                            step="0.01"

                                            value="{{ old(
                                                'scores.'.$student->id.'.midterm_score',
                                                $score->midterm_score ?? ''
                                            ) }}"

                                            {{ $locked ? 'readonly' : '' }}

                                            placeholder="—">

                                    </td>


                                    {{-- CUỐI KỲ --}}

                                    <td>

                                        <input
                                            type="number"
                                            name="scores[{{ $student->id }}][final_score]"
                                            class="form-control text-center score-input"
                                            min="0"
                                            max="10"
                                            step="0.01"

                                            value="{{ old(
                                                'scores.'.$student->id.'.final_score',
                                                $score->final_score ?? ''
                                            ) }}"

                                            {{ $locked ? 'readonly' : '' }}

                                            placeholder="—">

                                    </td>


                                    {{-- TB MÔN --}}

                                    <td class="text-center">

                                        @if(
                                            $score
                                            && $score->average_score !== null
                                        )

                                            <span class="fw-bold text-success">

                                                {{ number_format(
                                                    $score->average_score,
                                                    2
                                                ) }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- TRẠNG THÁI SỬA --}}

                                    <td class="text-center">

                                        @if(!$score)

                                            <span class="badge bg-secondary">
                                                Chưa có điểm
                                            </span>

                                        @elseif($locked)

                                            <span class="badge bg-danger">

                                                <i class="fa-solid fa-lock me-1"></i>

                                                Đã khóa 3/3

                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">

                                                Còn {{ $remaining }}/3 lần sửa

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="text-center py-5">

                                        <i class="fa-solid fa-users-slash fa-2x text-muted mb-3"></i>

                                        <div class="text-muted">

                                            Lớp này chưa có học sinh.

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                @if($students->count() > 0)

                    <div class="card-footer">

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="text-muted small">

                                <i class="fa-solid fa-circle-info me-1"></i>

                                Giáo viên được sửa điểm tối đa
                                <strong>3 lần</strong> cho mỗi bảng điểm.

                                Sau 3 lần, quyền sửa sẽ bị khóa.

                            </div>


                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa-solid fa-floppy-disk me-1"></i>

                                Lưu điểm

                            </button>

                        </div>

                    </div>

                @endif

            </form>

        </div>

    @endif


    {{-- =========================================================
         PHẦN GIÁO VIÊN CHỦ NHIỆM
    ========================================================== --}}

    @if(isset($homeroomData) && $homeroomData)

        <div class="card border-0 shadow-sm mb-5">

            <div class="card-header bg-warning">

                <h5 class="mb-1">

                    <i class="fa-solid fa-user-tie me-2"></i>

                    Tổng hợp lớp chủ nhiệm

                </h5>

                <small>
                    Theo dõi điểm tất cả các môn, hạnh kiểm và kết quả học tập
                </small>

            </div>


            <div class="card-body">

                @foreach($homeroomData as $homeroom)

                    @php

                        $class =
                            $homeroom['class'];

                        $classAssignment =
                            $homeroom['assignment'];

                    @endphp


                    <div class="mb-5">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>

                                <h5 class="fw-bold mb-1">

                                    Lớp:
                                    {{ $class->class_name }}

                                </h5>

                                <small class="text-muted">

                                    Giáo viên chủ nhiệm:
                                    {{ $teacher->full_name }}

                                </small>

                            </div>

                        </div>


                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-light text-center">

                                    <tr>

                                        <th>
                                            STT
                                        </th>

                                        <th>
                                            Học sinh
                                        </th>

                                        @foreach($allSubjects as $subject)

                                            <th>
                                                {{ $subject->subject_name }}
                                            </th>

                                        @endforeach

                                        <th>
                                            TB tất cả môn
                                        </th>

                                        <th>
                                            Hạnh kiểm
                                        </th>

                                        <th>
                                            Xếp loại
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse($homeroom['students'] as $index => $item)

                                        @php

                                            $student =
                                                $item['student'];

                                            $overall =
                                                $item['overall_average'];

                                        @endphp


                                        <tr>

                                            <td class="text-center">

                                                {{ $index + 1 }}

                                            </td>


                                            <td>

                                                <div class="fw-semibold">

                                                    {{ $student->full_name }}

                                                </div>

                                                <small class="text-muted">

                                                    {{ $student->student_code }}

                                                </small>

                                            </td>


                                            {{-- ĐIỂM TỪNG MÔN --}}

                                            @foreach($allSubjects as $subject)

                                                @php

                                                    $subjectAverage =
                                                        $item['subject_averages'][$subject->id]
                                                        ?? null;

                                                @endphp


                                                <td class="text-center">

                                                    @if($subjectAverage !== null)

                                                        <strong>

                                                            {{ number_format(
                                                                $subjectAverage,
                                                                2
                                                            ) }}

                                                        </strong>

                                                    @else

                                                        <span class="text-muted">
                                                            —
                                                        </span>

                                                    @endif

                                                </td>

                                            @endforeach


                                            {{-- TB TẤT CẢ MÔN --}}

                                            <td class="text-center">

                                                @if($overall !== null)

                                                    <span class="badge bg-success">

                                                        {{ number_format(
                                                            $overall,
                                                            2
                                                        ) }}

                                                    </span>

                                                @else

                                                    <span class="badge bg-secondary">

                                                        Chưa đủ điểm

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- HẠNH KIỂM --}}

                                            <td>
    <form
        method="POST"
        action="{{ route('teacher.scores.conduct.update') }}"
        class="d-flex align-items-center"
    >
        @csrf
        @method('PUT')

        <input
            type="hidden"
            name="student_id"
            value="{{ $student->id }}"
        >

<input
    type="hidden"
    name="school_year_id"
    value="{{ old('school_year_id', $schoolYear->id ?? request('school_year_id')) }}"
>

        <select
            name="conduct"
            class="form-select form-select-sm"
            onchange="this.form.submit()"
        >
            <option value="">
                Chọn
            </option>

            <option
                value="Tốt"
                {{ ($student->yearResult->conduct ?? '') === 'Tốt' ? 'selected' : '' }}
            >
                Tốt
            </option>

            <option
                value="Khá"
                {{ ($student->yearResult->conduct ?? '') === 'Khá' ? 'selected' : '' }}
            >
                Khá
            </option>

            <option
                value="Đạt"
                {{ ($student->yearResult->conduct ?? '') === 'Đạt' ? 'selected' : '' }}
            >
                Đạt
            </option>

            <option
                value="Chưa đạt"
                {{ ($student->yearResult->conduct ?? '') === 'Chưa đạt' ? 'selected' : '' }}
            >
                Chưa đạt
            </option>
        </select>
    </form>
</td>


                                            {{-- XẾP LOẠI --}}

                                            <td class="text-center">

                                                @if($item['classification'])

                                                    @if(
                                                        $item['classification']
                                                        === 'Hoàn thành xuất sắc'
                                                    )

                                                        <span class="badge bg-success">

                                                            Hoàn thành xuất sắc

                                                        </span>

                                                    @elseif(
                                                        $item['classification']
                                                        === 'Hoàn thành tốt'
                                                    )

                                                        <span class="badge bg-primary">

                                                            Hoàn thành tốt

                                                        </span>

                                                    @elseif(
                                                        $item['classification']
                                                        === 'Hoàn thành'
                                                    )

                                                        <span class="badge bg-info text-dark">

                                                            Hoàn thành

                                                        </span>

                                                    @else

                                                        <span class="badge bg-danger">

                                                            Chưa hoàn thành

                                                        </span>

                                                    @endif

                                                @else

                                                    <span class="text-muted">

                                                        Chưa xếp loại

                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="{{ 5 + $allSubjects->count() }}"
                                                class="text-center py-4">

                                                <span class="text-muted">

                                                    Lớp chưa có học sinh.

                                                </span>

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endif

</div>


{{-- =============================================================
     STYLE
============================================================= --}}

<style>

    .score-input {
        min-width: 85px;
    }

    .score-input:focus {
        border-color: #198754;
        box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .15);
    }

    table th {
        white-space: nowrap;
    }

    table td {
        vertical-align: middle;
    }

</style>

@endsection