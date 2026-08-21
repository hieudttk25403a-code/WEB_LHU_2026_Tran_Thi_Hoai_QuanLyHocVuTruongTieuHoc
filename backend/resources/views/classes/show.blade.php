@extends('layouts.app')

@section('content')

<div class="container py-4">


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-school me-2 text-primary"></i>

                Chi tiết lớp {{ $class->class_name }}

            </h3>

            <p class="text-muted mb-0">

                Thông tin và lịch sử của lớp học

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('classes.edit', $class->id) }}"
                class="btn btn-warning"
            >

                <i class="fas fa-edit me-1"></i>

                Chỉnh sửa

            </a>


            <a
                href="{{ route('classes.index') }}"
                class="btn btn-outline-secondary"
            >

                <i class="fas fa-arrow-left me-1"></i>

                Quay lại

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS --}}
    {{-- ========================================================= --}}

    @if (session('success'))

        <div class="alert alert-success">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">

                <i class="fas fa-exclamation-circle me-1"></i>

                Có lỗi xảy ra:

            </div>

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- THÔNG TIN CƠ BẢN --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">

                <i class="fas fa-info-circle me-2 text-primary"></i>

                Thông tin lớp

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Tên lớp --}}
                <div class="col-md-3">

                    <div class="text-muted small mb-1">
                        Tên lớp
                    </div>

                    <div class="fs-5 fw-bold">

                        {{ $class->class_name }}

                    </div>

                </div>


                {{-- Khối --}}
                <div class="col-md-3">

                    <div class="text-muted small mb-1">
                        Khối
                    </div>

                    <div class="fs-5 fw-bold">

                        Khối {{ $class->grade }}

                    </div>

                </div>


                {{-- Sĩ số --}}
                <div class="col-md-3">

                    <div class="text-muted small mb-1">
                        Sĩ số
                    </div>

                    <div class="fs-5 fw-bold">

                        {{ $studentCount ?? $class->student_count ?? 0 }}

                        <span class="text-muted fs-6">
                            học sinh
                        </span>

                    </div>

                </div>


                {{-- Trạng thái --}}
                <div class="col-md-3">

                    <div class="text-muted small mb-1">
                        Trạng thái
                    </div>

                    @php

                        $status = $class->status;

                        $statusClass = match ($status) {

                            'Đang nhập học' =>
                                'bg-success',

                            'Đã kết thúc năm học' =>
                                'bg-secondary',

                            'Chưa nhập học' =>
                                'bg-warning text-dark',

                            default =>
                                'bg-primary',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }}">

                        {{ $status }}

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GVCN HIỆN TẠI --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    <i class="fas fa-user-tie me-2 text-primary"></i>

                    Giáo viên chủ nhiệm

                </h5>


                <a
                    href="{{ route('class-assignments.create') }}"
                    class="btn btn-sm btn-primary"
                >

                    <i class="fas fa-user-plus me-1"></i>

                    Phân công GVCN

                </a>

            </div>

        </div>


        <div class="card-body">


            @if ($currentTeacher)

                <div class="row align-items-center">


                    <div class="col-md-1 text-center">

                        @if (!empty($currentTeacher->avatar))

                            <img
                                src="{{ asset('storage/' . $currentTeacher->avatar) }}"
                                class="rounded-circle"
                                width="60"
                                height="60"
                                style="object-fit: cover;"
                                alt="Avatar"
                            >

                        @else

                            <div
                                class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                style="width:60px;height:60px;"
                            >

                                <i class="fas fa-user fa-lg"></i>

                            </div>

                        @endif

                    </div>


                    <div class="col-md-5">

                        <div class="fw-bold fs-5">

                            {{ $currentTeacher->full_name }}

                        </div>

                        <div class="text-muted">

                            Mã GV:

                            <strong>
                                {{ $currentTeacher->teacher_code }}
                            </strong>

                        </div>

                    </div>


                    <div class="col-md-3">

                        @if ($activeYear)

                            <div class="small text-muted">
                                Năm học
                            </div>

                            <strong>

                                {{ $activeYear->name }}

                            </strong>

                        @else

                            <span class="text-muted">

                                Chưa xác định năm học

                            </span>

                        @endif

                    </div>


                    <div class="col-md-3 text-end">

                        <span class="badge bg-success px-3 py-2">

                            <i class="fas fa-check me-1"></i>

                            Đang chủ nhiệm

                        </span>

                    </div>

                </div>


            @else

                <div class="text-center py-4">

                    <div class="mb-3">

                        <i
                            class="fas fa-user-slash fa-3x text-muted"
                        ></i>

                    </div>

                    <h6 class="fw-bold">

                        Chưa có giáo viên chủ nhiệm

                    </h6>

                    <p class="text-muted mb-3">

                        Lớp này chưa được phân công giáo viên chủ nhiệm
                        trong năm học hiện tại.

                    </p>

                    <a
                        href="{{ route('class-assignments.create') }}"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-user-plus me-1"></i>

                        Phân công ngay

                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- LỊCH SỬ GVCN --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">

                <i class="fas fa-history me-2 text-secondary"></i>

                Lịch sử giáo viên chủ nhiệm

            </h5>

        </div>


        <div class="card-body">


            @if (
                isset($homeroomHistory)
                && $homeroomHistory->count() > 0
            )

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">
                                    STT
                                </th>

                                <th>
                                    Giáo viên
                                </th>

                                <th>
                                    Năm học
                                </th>

                                <th>
                                    Từ ngày
                                </th>

                                <th>
                                    Đến ngày
                                </th>

                                <th>
                                    Trạng thái
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach (
                                $homeroomHistory
                                as $index => $assignment
                            )

                                <tr>

                                    <td>

                                        {{ $index + 1 }}

                                    </td>


                                    <td>

                                        @if ($assignment->teacher)

                                            <div class="fw-bold">

                                                {{ $assignment->teacher->full_name }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $assignment->teacher->teacher_code }}

                                            </small>

                                        @else

                                            <span class="text-danger">

                                                Giáo viên không tồn tại

                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $assignment->schoolYear?->name ?? '—' }}

                                    </td>


                                    <td>

                                        @if ($assignment->start_date)

                                            {{ \Carbon\Carbon::parse($assignment->start_date)->format('d/m/Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if ($assignment->end_date)

                                            {{ \Carbon\Carbon::parse($assignment->end_date)->format('d/m/Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if ($assignment->end_date)

                                            <span class="badge bg-secondary">

                                                Đã kết thúc

                                            </span>

                                        @else

                                            <span class="badge bg-success">

                                                Đang chủ nhiệm

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="text-center text-muted py-4">

                    <i class="fas fa-history fa-2x mb-2"></i>

                    <div>

                        Chưa có lịch sử phân công giáo viên chủ nhiệm.

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DANH SÁCH HỌC SINH --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    <i class="fas fa-users me-2 text-primary"></i>

                    Danh sách học sinh

                </h5>


                <span class="badge bg-primary">

                    {{ $studentCount ?? $class->students->count() }}

                    học sinh

                </span>

            </div>

        </div>


        <div class="card-body p-0">


            @if ($class->students && $class->students->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">
                                    STT
                                </th>

                                <th>
                                    Mã HS
                                </th>

                                <th>
                                    Họ và tên
                                </th>

                                <th>
                                    Ngày sinh
                                </th>

                                <th>
                                    Giới tính
                                </th>

                                <th>
                                    Số điện thoại
                                </th>

                                <th>
                                    Trạng thái
                                </th>

                                <th width="100">
                                    Thao tác
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach (
                                $class->students
                                as $index => $student
                            )

                                <tr>

                                    <td>

                                        {{ $index + 1 }}

                                    </td>


                                    <td>

                                        <strong>

                                            {{ $student->student_code }}

                                        </strong>

                                    </td>


                                    <td>

                                        {{ $student->full_name }}

                                    </td>


                                    <td>

                                        @if ($student->date_of_birth)

                                            {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        {{ $student->gender ?? '—' }}

                                    </td>


                                    <td>

                                        {{ $student->phone ?? '—' }}

                                    </td>


                                    <td>

                                        @php

                                            $studentStatus =
                                                mb_strtolower(
                                                    trim($student->status ?? '')
                                                );

                                            if (
                                                $studentStatus === 'đang học'
                                            ) {

                                                $studentBadge =
                                                    'bg-success';

                                            } elseif (
                                                $studentStatus === 'chuyển trường'
                                                ||
                                                $studentStatus === 'đuổi học'
                                            ) {

                                                $studentBadge =
                                                    'bg-danger';

                                            } elseif (
                                                $studentStatus === 'bảo lưu'
                                            ) {

                                                $studentBadge =
                                                    'bg-warning text-dark';

                                            } else {

                                                $studentBadge =
                                                    'bg-secondary';

                                            }

                                        @endphp


                                        <span class="badge {{ $studentBadge }}">

                                            {{ $student->status ?? '—' }}

                                        </span>

                                    </td>


                                    <td>

                                        @if (
                                            Route::has('students.show')
                                        )

                                            <a
                                                href="{{ route('students.show', $student->id) }}"
                                                class="btn btn-sm btn-info text-white"
                                                title="Xem hồ sơ"
                                            >

                                                <i class="fas fa-eye"></i>

                                            </a>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="text-center py-5">

                    <i
                        class="fas fa-user-graduate fa-3x text-muted mb-3"
                    ></i>

                    <h6 class="fw-bold">

                        Chưa có học sinh

                    </h6>

                    <p class="text-muted mb-0">

                        Hiện chưa có học sinh nào được phân vào lớp này.

                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GIÁO VIÊN BỘ MÔN --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">

                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>

                Giáo viên bộ môn của lớp

            </h5>

        </div>


        <div class="card-body">

            @if (
                isset($class->teacherSubjectAssignments)
                &&
                $class->teacherSubjectAssignments->count() > 0
            )

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>STT</th>

                                <th>Giáo viên</th>

                                <th>Môn học</th>

                                <th>Năm học</th>

                                <th>Thời gian</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach (
                                $class->teacherSubjectAssignments
                                as $index => $assignment
                            )

                                <tr>

                                    <td>

                                        {{ $index + 1 }}

                                    </td>


                                    <td>

                                        @if ($assignment->teacher)

                                            <strong>

                                                {{ $assignment->teacher->full_name }}

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                {{ $assignment->teacher->teacher_code }}

                                            </small>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        {{ $assignment->subject?->subject_name ?? '—' }}

                                    </td>


                                    <td>

                                        {{ $assignment->schoolYear?->name ?? '—' }}

                                    </td>


                                    <td>

                                        @if ($assignment->start_date)

                                            {{ \Carbon\Carbon::parse($assignment->start_date)->format('d/m/Y') }}

                                        @else

                                            —

                                        @endif

                                        -

                                        @if ($assignment->end_date)

                                            {{ \Carbon\Carbon::parse($assignment->end_date)->format('d/m/Y') }}

                                        @else

                                            hiện tại

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center text-muted py-4">

                    <i class="fas fa-chalkboard fa-2x mb-2"></i>

                    <div>

                        Chưa có giáo viên bộ môn được phân công cho lớp này.

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- XÓA LỚP --}}
    {{-- ========================================================= --}}

    <div class="card border-danger shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="fw-bold text-danger mb-1">

                        Khu vực nguy hiểm

                    </h6>

                    <small class="text-muted">

                        Chỉ xóa lớp khi lớp không còn học sinh,
                        lịch sử GVCN hoặc phân công giáo viên bộ môn.

                    </small>

                </div>


                <form
                    action="{{ route('classes.destroy', $class->id) }}"
                    method="POST"
                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa lớp {{ $class->class_name }} không?');"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >

                        <i class="fas fa-trash me-1"></i>

                        Xóa lớp

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection