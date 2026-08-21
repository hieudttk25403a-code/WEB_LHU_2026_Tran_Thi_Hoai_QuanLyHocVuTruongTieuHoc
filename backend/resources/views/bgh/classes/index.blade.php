@extends('layouts.app')

@section('title', 'Danh sách lớp học')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
         TIÊU ĐỀ
    ====================================================== --}}

    <div class="mb-4">

        <h2 class="fw-bold mb-1">

            <i class="fa-solid fa-school text-primary me-2"></i>

            Danh sách lớp học

        </h2>

        <p class="text-muted mb-0">

            Xem thông tin các lớp học trong toàn trường

        </p>

    </div>


    {{-- =====================================================
         TÌM KIẾM / LỌC
    ====================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('bgh.classes.index') }}"
            >

                <div class="row g-3 align-items-end">


                    {{-- TÊN LỚP --}}

                    <div class="col-md-5">

                        <label class="form-label fw-semibold">

                            Tìm lớp

                        </label>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            value="{{ request('keyword') }}"
                            placeholder="Nhập tên lớp..."
                        >

                    </div>


                    {{-- KHỐI --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Khối

                        </label>

                        <select
                            name="grade"
                            class="form-select"
                        >

                            <option value="">

                                -- Tất cả khối --

                            </option>

                            @foreach($grades as $grade)

                                <option
                                    value="{{ $grade }}"
                                    {{ request('grade') == $grade ? 'selected' : '' }}
                                >

                                    Khối {{ $grade }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TÌM --}}

                    <div class="col-md-4">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fa-solid fa-magnifying-glass me-1"></i>

                                Tìm kiếm

                            </button>


                            <a
                                href="{{ route('bgh.classes.index') }}"
                                class="btn btn-outline-secondary"
                            >

                                <i class="fa-solid fa-rotate-left me-1"></i>

                                Đặt lại

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         DANH SÁCH
    ====================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>

                    <i class="fa-solid fa-list me-2"></i>

                    Danh sách lớp

                </strong>

                <span>

                    Tổng:
                    {{ $classes->total() }}
                    lớp

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

                        @forelse($classes as $index => $class)

                            <tr>


                                {{-- STT --}}

                                <td class="text-center">

                                    {{
                                        ($classes->currentPage() - 1)
                                        * $classes->perPage()
                                        + $index
                                        + 1
                                    }}

                                </td>


                                {{-- TÊN LỚP --}}

                                <td>

                                    <strong>

                                        {{ $class->class_name }}

                                    </strong>

                                </td>


                                {{-- KHỐI --}}

                                <td>

                                    <span class="badge bg-info text-dark">

                                        Khối {{ $class->grade }}

                                    </span>

                                </td>


                                {{-- GVCN --}}

                                <td>

                                    @if(!empty($class->homeroom_teacher))

                                        {{ $class->homeroom_teacher }}

                                    @elseif(
                                        isset($class->homeroomTeacher)
                                        && $class->homeroomTeacher
                                    )

                                        {{ $class->homeroomTeacher->full_name }}

                                    @else

                                        <span class="text-muted">

                                            Chưa phân công

                                        </span>

                                    @endif

                                </td>


                                {{-- SĨ SỐ --}}

                                <td class="text-center">

                                    @if(isset($class->student_count))

                                        <span class="badge bg-success">

                                            {{ $class->student_count }}

                                        </span>

                                    @else

                                        <span class="badge bg-success">

                                            {{ $class->students()->count() }}

                                        </span>

                                    @endif

                                </td>


                                {{-- TRẠNG THÁI --}}

                                <td>

                                    @if(
                                        isset($class->status)
                                        && $class->status
                                    )

                                        <span class="badge bg-success">

                                            {{ $class->status }}

                                        </span>

                                    @else

                                        <span class="badge bg-success">

                                            Đang hoạt động

                                        </span>

                                    @endif

                                </td>


                                {{-- XEM --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route('bgh.classes.show', $class) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        <i class="fa-solid fa-eye me-1"></i>

                                        Xem

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="fa-solid fa-school text-muted"
                                        style="font-size:42px;"
                                    ></i>

                                    <p class="text-muted mt-3 mb-0">

                                        Không tìm thấy lớp học.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PHÂN TRANG --}}

        @if($classes->hasPages())

            <div class="card-footer bg-white">

                {{ $classes->links() }}

            </div>

        @endif

    </div>

</div>

@endsection