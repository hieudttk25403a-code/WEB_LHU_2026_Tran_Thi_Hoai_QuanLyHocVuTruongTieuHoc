@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-user-graduate text-success me-2"></i>
                Hồ sơ học sinh
            </h3>
            <p class="text-muted mb-0">
                Thông tin chi tiết của {{ $student->full_name }}
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Quay lại
            </a>

            <a href="{{ route('students.edit', $student) }}" class="btn btn-success">
                <i class="fas fa-edit me-1"></i>
                Chỉnh sửa
            </a>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- THÔNG TIN CƠ BẢN --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-id-card text-success me-2"></i>
                Thông tin cá nhân
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- AVATAR --}}
                <div class="col-md-3 text-center">

                    <div class="mb-3">

                        @if(!empty($student->avatar))
                            <img src="{{ asset('storage/' . $student->avatar) }}"
                                 class="rounded-circle shadow-sm"
                                 style="width:150px;height:150px;object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                 style="width:150px;height:150px;">
                                <i class="fas fa-user-graduate fa-4x text-secondary"></i>
                            </div>
                        @endif

                    </div>

                    <h5 class="fw-bold mb-1">
                        {{ $student->full_name }}
                    </h5>

                    <div class="text-muted">
                        {{ $student->student_code }}
                    </div>

                    <div class="mt-2">

                        @if($student->status === 'Đang học')
                            <span class="badge bg-success">
                                Đang học
                            </span>
                        @elseif($student->status === 'Chuyển trường')
                            <span class="badge bg-warning text-dark">
                                Chuyển trường
                            </span>
                        @elseif($student->status === 'Đuổi học')
                            <span class="badge bg-danger">
                                Đuổi học
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                {{ $student->status }}
                            </span>
                        @endif

                    </div>

                </div>


                {{-- THÔNG TIN --}}
                <div class="col-md-9">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="info-box">
                                <small class="text-muted">
                                    Mã học sinh
                                </small>
                                <div class="fw-semibold">
                                    {{ $student->student_code }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small class="text-muted">
                                    Họ và tên
                                </small>
                                <div class="fw-semibold">
                                    {{ $student->full_name }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small class="text-muted">
                                    Ngày sinh
                                </small>
                                <div class="fw-semibold">
                                    {{ $student->date_of_birth
                                        ? $student->date_of_birth->format('d/m/Y')
                                        : '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small class="text-muted">
                                    Giới tính
                                </small>
                                <div class="fw-semibold">
                                    {{ $student->gender ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small class="text-muted">
                                    Số điện thoại
                                </small>
                                <div class="fw-semibold">
                                    {{ $student->phone ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small class="text-muted">
                                    Email
                                </small>
                                <div class="fw-semibold">
                                    {{ $student->email ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="info-box">
                                <small class="text-muted">
                                    Địa chỉ
                                </small>
                                <div class="fw-semibold">
                                    {{ $student->address ?? '—' }}
                                </div>
                            </div>
                        </div>

                        {{-- LỚP HIỆN TẠI --}}
                        <div class="col-md-6">
                            <div class="info-box border-success">

                                <small class="text-muted">
                                    Lớp hiện tại
                                </small>

                                @if($student->schoolClass)
                                    <div class="fw-bold text-success">
                                        {{ $student->schoolClass->class_name }}
                                    </div>

                                    <small class="text-muted">
                                        Khối {{ $student->schoolClass->grade }}
                                    </small>
                                @else
                                    <div class="text-danger">
                                        Chưa phân lớp
                                    </div>
                                @endif

                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>


    {{-- LỊCH SỬ LỚP --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    <i class="fas fa-history text-primary me-2"></i>
                    Lịch sử lớp học
                </h5>

                <span class="badge bg-primary">
                    {{ $student->classHistories->count() }} năm
                </span>

            </div>

        </div>

        <div class="card-body">

            @if($student->classHistories->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th width="60">#</th>
                                <th>Năm học</th>
                                <th>Lớp</th>
                                <th>Khối</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                                <th>Ghi chú</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($student->classHistories as $history)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        @if($history->schoolYear)
                                            <strong>
                                                {{ $history->schoolYear->name }}
                                            </strong>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>

                                        @if($history->schoolClass)

                                            <span class="badge bg-success-subtle text-success fs-6">
                                                {{ $history->schoolClass->class_name }}
                                            </span>

                                        @else
                                            —
                                        @endif

                                    </td>

                                    <td>

                                        @if($history->schoolClass)
                                            Khối {{ $history->schoolClass->grade }}
                                        @else
                                            —
                                        @endif

                                    </td>

                                    <td>

                                        @switch($history->status)

                                            @case('Đang học')
                                                <span class="badge bg-success">
                                                    Đang học
                                                </span>
                                                @break

                                            @case('Lên lớp')
                                                <span class="badge bg-primary">
                                                    Lên lớp
                                                </span>
                                                @break

                                            @case('Ở lại lớp')
                                                <span class="badge bg-warning text-dark">
                                                    Ở lại lớp
                                                </span>
                                                @break

                                            @case('Chuyển lớp')
                                                <span class="badge bg-info text-dark">
                                                    Chuyển lớp
                                                </span>
                                                @break

                                            @case('Bảo lưu')
                                                <span class="badge bg-secondary">
                                                    Bảo lưu
                                                </span>
                                                @break

                                            @case('Chuyển trường')
                                                <span class="badge bg-warning text-dark">
                                                    Chuyển trường
                                                </span>
                                                @break

                                            @case('Đuổi học')
                                                <span class="badge bg-danger">
                                                    Đuổi học
                                                </span>
                                                @break

                                            @default
                                                <span class="badge bg-secondary">
                                                    {{ $history->status ?? '—' }}
                                                </span>

                                        @endswitch

                                    </td>

                                    <td>

                                        @if($history->start_date)
                                            {{ $history->start_date->format('d/m/Y') }}
                                        @endif

                                        @if($history->end_date)
                                            -
                                            {{ $history->end_date->format('d/m/Y') }}
                                        @endif

                                    </td>

<td>
    @php
        $homeroomTeacher = null;

        if ($history->schoolClass) {
            $assignment = \App\Models\ClassAssignment::with('teacher')
                ->where('class_id', $history->schoolClass->id)
                ->where('school_year_id', $history->school_year_id)
                ->first();

            if ($assignment && $assignment->teacher) {
                $homeroomTeacher = $assignment->teacher;
            }
        }
    @endphp

    @if($homeroomTeacher)
        GVCN: {{ $homeroomTeacher->full_name }}
    @else
        Chưa phân công GVCN
    @endif
</td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-4 text-muted">

                    <i class="fas fa-history fa-2x mb-2"></i>

                    <p class="mb-0">
                        Chưa có lịch sử lớp học.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- PHỤ HUYNH --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    <i class="fas fa-users text-success me-2"></i>
                    Thông tin phụ huynh
                </h5>

                <a href="{{ route('student-parents.create', $student) }}"
                   class="btn btn-sm btn-success">

                    <i class="fas fa-plus me-1"></i>
                    Thêm phụ huynh

                </a>

            </div>

        </div>

        <div class="card-body">

            @if($student->parents->count())

                <div class="row g-3">

                    @foreach($student->parents as $parent)

                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="d-flex justify-content-between">

                                    <h6 class="fw-bold mb-3">
                                        {{ $parent->full_name }}
                                    </h6>

                                    <a href="{{ route(
                                        'student-parents.edit',
                                        [$student, $parent]
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                </div>

                                <div class="mb-2">
                                    <small class="text-muted">
                                        Quan hệ:
                                    </small>
                                    {{ $parent->relationship ?? '—' }}
                                </div>

                                <div class="mb-2">
                                    <small class="text-muted">
                                        Nghề nghiệp:
                                    </small>
                                    {{ $parent->occupation ?? '—' }}
                                </div>

                                <div class="mb-2">
                                    <small class="text-muted">
                                        Điện thoại:
                                    </small>
                                    {{ $parent->phone ?? '—' }}
                                </div>

                                <div>
                                    <small class="text-muted">
                                        Email:
                                    </small>
                                    {{ $parent->email ?? '—' }}
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center text-muted py-3">
                    Chưa có thông tin phụ huynh.
                </div>

            @endif

        </div>

    </div>


    {{-- SỨC KHỎE --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    <i class="fas fa-heartbeat text-danger me-2"></i>
                    Hồ sơ sức khỏe
                </h5>

                @if($student->healthProfile)
                    <a href="{{ route(
                        'student-health.edit',
                        [$student, $student->healthProfile]
                    ) }}"
                       class="btn btn-sm btn-outline-primary">

                        <i class="fas fa-edit me-1"></i>
                        Chỉnh sửa

                    </a>
                @endif

            </div>

        </div>

        <div class="card-body">

            @if($student->healthProfile)

                <div class="row g-3">

                    <div class="col-md-3">
                        <div class="info-box">
                            <small class="text-muted">
                                Chiều cao
                            </small>

                            <div class="fw-bold">
                                {{ $student->healthProfile->height ?? '—' }}
                                cm
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="info-box">
                            <small class="text-muted">
                                Cân nặng
                            </small>

                            <div class="fw-bold">
                                {{ $student->healthProfile->weight ?? '—' }}
                                kg
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="info-box">
                            <small class="text-muted">
                                Nhóm máu
                            </small>

                            <div class="fw-bold">
                                {{ $student->healthProfile->blood_type ?? '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="info-box">
                            <small class="text-muted">
                                Dị ứng
                            </small>

                            <div class="fw-bold">
                                {{ $student->healthProfile->allergy ?? 'Không' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">

                        <div class="info-box">

                            <small class="text-muted">
                                Ghi chú
                            </small>

                            <div>
                                {{ $student->healthProfile->note ?? 'Không có' }}
                            </div>

                        </div>

                    </div>

                </div>

            @else

                <div class="text-center py-3 text-muted">

                    <p>
                        Chưa có hồ sơ sức khỏe.
                    </p>

                    <a href="{{ route(
                        'student-health.create',
                        $student
                    ) }}"
                       class="btn btn-success">

                        <i class="fas fa-plus me-1"></i>
                        Thêm hồ sơ sức khỏe

                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- BẢNG ĐIỂM --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-0">
                <i class="fas fa-graduation-cap text-primary me-2"></i>
                Kết quả học tập
            </h5>

        </div>

        <div class="card-body">

            @if($student->scores->count())

                @php
                    $groupedScores = $student->scores
                        ->groupBy(function ($score) {
                            return optional($score->schoolYear)->name
                                ?? 'Chưa xác định';
                        });
                @endphp

                @foreach($groupedScores as $year => $scores)

                    <h6 class="fw-bold mt-2 mb-3">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Năm học {{ $year }}
                    </h6>

                    <div class="table-responsive mb-4">

                        <table class="table table-bordered align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Môn học</th>
                                    <th>Giáo viên</th>
                                    <th>Miệng</th>
                                    <th>15 phút</th>
                                    <th>Giữa kỳ</th>
                                    <th>Cuối kỳ</th>
                                    <th>TB</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($scores as $score)

                                    <tr>

                                        <td>
                                            {{ optional($score->subject)->subject_name ?? '—' }}
                                        </td>

<td>

    @php

        $assignedTeacher =
            \App\Models\TeacherSubjectAssignment::with('teacher')
                ->where(
                    'subject_id',
                    $score->subject_id
                )
                ->where(
                    'class_id',
                    $student->class_id
                )
                ->where(
                    'school_year_id',
                    $score->school_year_id
                )
                ->first();

    @endphp

    @if($assignedTeacher && $assignedTeacher->teacher)

        {{ $assignedTeacher->teacher->full_name }}

    @else

        <span class="text-muted">
            Chưa phân công
        </span>

    @endif

</td>

                                        <td>
                                            {{ $score->oral_score ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $score->fifteen_minute_score ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $score->midterm_score ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $score->final_score ?? '—' }}
                                        </td>

                                        <td class="fw-bold">
                                            {{ $score->average_score ?? '—' }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @endforeach

            @else

                <div class="text-center text-muted py-3">
                    Chưa có dữ liệu điểm.
                </div>

            @endif

        </div>

    </div>
{{-- ================= KẾT QUẢ RÈN LUYỆN ================= --}}
<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">
            <i class="fa-solid fa-user-check me-2"></i>
            Kết quả rèn luyện
        </h5>

    </div>

    <div class="card-body">

        @php

            $activeYear =
                \App\Models\SchoolYear::where(
                    'is_active',
                    1
                )->first();

            $yearResult = null;

            if ($activeYear) {

                $yearResult =
                    $student->yearResults
                        ->firstWhere(
                            'school_year_id',
                            $activeYear->id
                        );

            }

        @endphp


        @if($activeYear)

            <div class="mb-3">

                <div class="fw-bold">
                    <i class="fa-solid fa-calendar me-1"></i>
                    Năm học {{ $activeYear->name }}
                </div>

            </div>


            <div class="row">

                <div class="col-md-6">

                    <div class="border rounded p-3">

                        <div class="text-muted small mb-1">
                            Hạnh kiểm
                        </div>

                        @if(
                            $yearResult &&
                            $yearResult->conduct
                        )

                            @php

                                $conductClass = match(
                                    $yearResult->conduct
                                ) {

                                    'Tốt' =>
                                        'bg-success',

                                    'Khá' =>
                                        'bg-primary',

                                    'Đạt' =>
                                        'bg-warning text-dark',

                                    'Chưa đạt' =>
                                        'bg-danger',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp

                            <span
                                class="badge {{ $conductClass }}"
                                style="font-size: 14px;"
                            >
                                {{ $yearResult->conduct }}
                            </span>

                        @else

                            <span class="text-muted">
                                Chưa xếp
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        @else

            <div class="text-muted">
                Chưa xác định năm học.
            </div>

        @endif

    </div>

</div>

</div>


<style>

    .info-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 14px 16px;
        height: 100%;
    }

    .info-box small {
        display: block;
        margin-bottom: 5px;
    }

    .card {
        border-radius: 14px;
    }

    .card-header {
        border-radius: 14px 14px 0 0 !important;
    }

    .table th {
        white-space: nowrap;
    }

</style>

@endsection