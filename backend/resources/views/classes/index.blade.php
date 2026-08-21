@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">


    {{-- HEADER --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Quản lý lớp học
            </h3>

            <p class="text-muted mb-0">
                Danh sách các lớp học trong trường
            </p>

        </div>


        <a
            href="{{ route('classes.create') }}"
            class="btn btn-success"
        >

            <i class="fas fa-plus me-1"></i>

            Thêm lớp học

        </a>

    </div>


    {{-- THÔNG BÁO --}}

    @if (session('success'))

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


    @if (session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- BỘ LỌC --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('classes.index') }}"
            >

                <div class="row g-3">


                    {{-- Tìm kiếm --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Tìm kiếm
                        </label>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            value="{{ request('keyword') }}"
                            placeholder="Tên lớp..."
                        >

                    </div>


                    {{-- Khối --}}

                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Khối
                        </label>

                        <select
                            name="grade"
                            class="form-select"
                        >

                            <option value="">
                                Tất cả
                            </option>

                            @foreach ($grades as $grade)

                                <option
                                    value="{{ $grade }}"
                                    {{ request('grade') == $grade ? 'selected' : '' }}
                                >
                                    Khối {{ $grade }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Năm học --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Năm học
                        </label>

                        <select
                            name="school_year_id"
                            class="form-select"
                        >

                            <option value="">
                                -- Năm học hiện tại --
                            </option>

                            @foreach ($schoolYears as $year)

                                <option
                                    value="{{ $year->id }}"
                                    {{ request('school_year_id') == $year->id ? 'selected' : '' }}
                                >
                                    {{ $year->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Button --}}

                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="fas fa-search me-1"></i>

                            Tìm kiếm

                        </button>

                    </div>


                </div>

            </form>

        </div>

    </div>


    {{-- NĂM HỌC ĐANG XEM --}}

    @if ($schoolYear)

        <div class="alert alert-info">

            <i class="fas fa-calendar-alt me-2"></i>

            Đang xem lớp học của năm học:

            <strong>
                {{ $schoolYear->name }}
            </strong>

        </div>

    @endif


    {{-- DANH SÁCH --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between">

                <h5 class="mb-0 fw-bold">

                    <i class="fas fa-school me-2 text-success"></i>

                    Danh sách lớp học

                </h5>


                <span class="badge bg-success">

                    {{ $classes->total() }} lớp

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="text-center">
                                STT
                            </th>

                            <th>
                                Tên lớp
                            </th>

                            <th>
                                Khối
                            </th>

                            <th>
                                Giáo viên chủ nhiệm
                            </th>

                            <th class="text-center">
                                Sĩ số
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th class="text-center">
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse ($classes as $index => $class)

                        <tr>


                            {{-- STT --}}

                            <td class="text-center">

                                {{ $classes->firstItem() + $index }}

                            </td>


                            {{-- Tên lớp --}}

                            <td>

                                <strong>
                                    {{ $class->class_name }}
                                </strong>

                            </td>


                            {{-- Khối --}}

                            <td>

                                <span class="badge bg-light text-dark border">

                                    Khối {{ $class->grade }}

                                </span>

                            </td>


                            {{-- GVCN --}}

                            <td>

                                @if ($class->current_assignment && $class->current_assignment->teacher)

                                    <div>

                                        <span class="fw-semibold text-success">

                                            {{ $class->current_assignment->teacher->full_name }}

                                        </span>

                                        <br>

                                        <small class="text-muted">

                                            {{ $class->current_assignment->teacher->teacher_code }}

                                        </small>

                                    </div>

                                @else

                                    <span class="text-muted">

                                        Chưa phân công

                                    </span>

                                @endif


                                {{-- Lịch sử GVCN --}}

                                @if ($class->assignment_history && $class->assignment_history->count() > 0)

                                    <div class="mt-1">

                                        @foreach ($class->assignment_history as $history)

                                            @if (
                                                !$class->current_assignment ||
                                                $history->id != $class->current_assignment->id
                                            )

                                                @if ($history->teacher)

                                                    <small class="text-secondary">

                                                        <i class="fas fa-history me-1"></i>

                                                        {{ $history->teacher->full_name }}

                                                        <span class="badge bg-secondary">
                                                            Cũ
                                                        </span>

                                                    </small>

                                                @endif

                                            @endif

                                        @endforeach

                                    </div>

                                @endif

                            </td>


                            {{-- Sĩ số --}}

                            <td class="text-center">

                                <span class="badge bg-primary">

                                    {{ $class->student_count }}

                                </span>

                            </td>


                            {{-- Trạng thái --}}

                            <td>

                                @if ($class->status === 'Đang hoạt động')

                                    <span class="badge bg-success">

                                        Đang hoạt động

                                    </span>

                                @elseif ($class->status === 'Đang nhập học')

                                    <span class="badge bg-primary">

                                        Đang nhập học

                                    </span>

                                @elseif ($class->status === 'Đã kết thúc năm học')

                                    <span class="badge bg-secondary">

                                        Đã kết thúc năm học

                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">

                                        {{ $class->status }}

                                    </span>

                                @endif

                            </td>


                            {{-- Thao tác --}}

                            <td class="text-center">

                                <div class="btn-group">


                                    <a
                                        href="{{ route('classes.show', $class->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Xem chi tiết"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    <a
                                        href="{{ route('classes.edit', $class->id) }}"
                                        class="btn btn-sm btn-outline-warning"
                                        title="Chỉnh sửa"
                                    >

                                        <i class="fas fa-edit"></i>

                                    </a>


                                    <form
                                        action="{{ route('classes.destroy', $class->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa lớp này không?');"
                                        class="d-inline"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Xóa"
                                        >

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>


                                </div>

                            </td>


                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <i
                                    class="fas fa-school fa-2x text-muted mb-3"
                                ></i>

                                <p class="text-muted mb-0">

                                    Chưa có lớp học.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if ($classes->hasPages())

            <div class="card-footer bg-white">

                {{ $classes->links() }}

            </div>

        @endif

    </div>

</div>

@endsection