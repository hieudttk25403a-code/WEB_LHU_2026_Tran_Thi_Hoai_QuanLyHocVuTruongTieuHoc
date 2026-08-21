@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Phân công giáo viên chủ nhiệm
            </h2>

            <p class="text-muted mb-0">
                Quản lý giáo viên chủ nhiệm theo lớp và năm học
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('teachers.assignment') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Phân công giáo viên
            </a>

            <a
                href="{{ route('class-assignments.create') }}"
                class="btn btn-primary"
            >
                <i class="fas fa-plus me-1"></i>
                Phân công chủ nhiệm
            </a>

        </div>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ERROR --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('class-assignments.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- KEYWORD --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Tìm giáo viên
                        </label>

                        <input
                            type="text"
                            name="keyword"
                            value="{{ request('keyword') }}"
                            class="form-control"
                            placeholder="Mã giáo viên hoặc họ tên..."
                        >

                    </div>


                    {{-- CLASS --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Lớp
                        </label>

                        <select
                            name="class_id"
                            class="form-select"
                        >

                            <option value="">
                                -- Tất cả lớp --
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}
                                >
                                    {{ $class->class_name }}

                                    @if(isset($class->grade))
                                        - Khối {{ $class->grade }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SCHOOL YEAR --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Năm học
                        </label>

                        <select
                            name="school_year_id"
                            class="form-select"
                        >

                            <option value="">
                                -- Tất cả năm học --
                            </option>

                            @foreach($schoolYears as $schoolYear)

                                <option
                                    value="{{ $schoolYear->id }}"
                                    {{ request('school_year_id') == $schoolYear->id ? 'selected' : '' }}
                                >

                                    {{ $schoolYear->name
                                        ?? $schoolYear->school_year
                                        ?? ($schoolYear->start_year . ' - ' . $schoolYear->end_year)
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BUTTON --}}
                    <div class="col-md-2">

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


    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                STT
                            </th>

                            <th>
                                Mã giáo viên
                            </th>

                            <th>
                                Giáo viên
                            </th>

                            <th>
                                Lớp chủ nhiệm
                            </th>

                            <th>
                                Năm học
                            </th>

                            <th class="text-center">
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($assignments as $assignment)

                            <tr>

                                <td class="px-3">

                                    {{
                                        ($assignments->currentPage() - 1)
                                        * $assignments->perPage()
                                        + $loop->iteration
                                    }}

                                </td>


                                {{-- TEACHER CODE --}}
                                <td>

                                    @if($assignment->teacher)

                                        <span class="badge bg-secondary">

                                            {{ $assignment->teacher->teacher_code }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            Không có
                                        </span>

                                    @endif

                                </td>


                                {{-- TEACHER --}}
                                <td>

                                    @if($assignment->teacher)

                                        <div class="fw-semibold">

                                            {{ $assignment->teacher->full_name }}

                                        </div>

                                    @else

                                        <span class="text-danger">
                                            Giáo viên không tồn tại
                                        </span>

                                    @endif

                                </td>


                                {{-- CLASS --}}
                                <td>

                                    @if($assignment->schoolClass)

                                        <span class="fw-semibold">

                                            {{ $assignment->schoolClass->class_name }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            Không có lớp
                                        </span>

                                    @endif

                                </td>


                                {{-- SCHOOL YEAR --}}
                                <td>

                                    @if($assignment->schoolYear)

                                        {{ $assignment->schoolYear->name
                                            ?? $assignment->schoolYear->school_year
                                            ?? (
                                                $assignment->schoolYear->start_year
                                                . ' - ' .
                                                $assignment->schoolYear->end_year
                                            )
                                        }}

                                    @else

                                        <span class="text-muted">
                                            Không có
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-1">

                                        <a
                                            href="{{ route(
                                                'class-assignments.show',
                                                $assignment
                                            ) }}"
                                            class="btn btn-sm btn-outline-info"
                                            title="Xem"
                                        >

                                            <i class="fas fa-eye"></i>

                                        </a>


                                        <a
                                            href="{{ route(
                                                'class-assignments.edit',
                                                $assignment
                                            ) }}"
                                            class="btn btn-sm btn-outline-warning"
                                            title="Sửa"
                                        >

                                            <i class="fas fa-edit"></i>

                                        </a>


                                        <form
                                            action="{{ route(
                                                'class-assignments.destroy',
                                                $assignment
                                            ) }}"
                                            method="POST"
                                            onsubmit="return confirm(
                                                'Bạn có chắc muốn xóa phân công này?'
                                            );"
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
                                    colspan="6"
                                    class="text-center py-5 text-muted"
                                >

                                    <i
                                        class="fas fa-user-tie mb-2"
                                        style="font-size: 30px;"
                                    ></i>

                                    <div>
                                        Chưa có phân công giáo viên chủ nhiệm.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($assignments->hasPages())

            <div class="card-footer bg-white">

                {{ $assignments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection