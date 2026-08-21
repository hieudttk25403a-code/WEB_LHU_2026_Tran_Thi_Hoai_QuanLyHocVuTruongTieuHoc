@extends('layouts.app')

@section('title', 'Danh sách học sinh')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
         TIÊU ĐỀ
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                <i class="fa-solid fa-user-graduate text-primary me-2"></i>
                Danh sách học sinh
            </h2>

            <p class="text-muted mb-0">
                Xem thông tin học sinh toàn trường
            </p>

        </div>

    </div>


    {{-- =====================================================
         THÔNG BÁO
    ====================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =====================================================
         TÌM KIẾM
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('bgh.students.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- TÌM KIẾM --}}

                    <div class="col-md-5">

                        <label class="form-label fw-semibold">

                            Tìm học sinh

                        </label>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            value="{{ request('keyword') }}"
                            placeholder="Nhập tên hoặc mã học sinh..."
                        >

                    </div>


                    {{-- LỚP --}}

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

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TRẠNG THÁI --}}

                    <div class="col-md-2">

                        <label class="form-label fw-semibold">

                            Trạng thái

                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                -- Tất cả --
                            </option>

                            <option
                                value="Đang học"
                                {{ request('status') == 'Đang học' ? 'selected' : '' }}
                            >
                                Đang học
                            </option>

                            <option
                                value="Chuyển trường"
                                {{ request('status') == 'Chuyển trường' ? 'selected' : '' }}
                            >
                                Chuyển trường
                            </option>

                            <option
                                value="Đuổi học"
                                {{ request('status') == 'Đuổi học' ? 'selected' : '' }}
                            >
                                Đuổi học
                            </option>

                        </select>

                    </div>


                    {{-- NÚT --}}

                    <div class="col-md-2">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >

                                <i class="fa-solid fa-magnifying-glass me-1"></i>

                                Tìm kiếm

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         BẢNG HỌC SINH
    ====================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>

                    <i class="fa-solid fa-list me-2"></i>

                    Danh sách học sinh

                </strong>

                <span>

                    Tổng:
                    {{ $students->total() }}
                    học sinh

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
                                Mã học sinh
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
                                Lớp
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

                        @forelse($students as $index => $student)

                            <tr>

                                <td class="text-center">

                                    {{
                                        ($students->currentPage() - 1)
                                        * $students->perPage()
                                        + $index
                                        + 1
                                    }}

                                </td>


                                <td>

                                    <span class="fw-semibold">

                                        {{ $student->student_code }}

                                    </span>

                                </td>


                                <td>

                                    <strong>

                                        {{ $student->full_name }}

                                    </strong>

                                </td>


                                <td>

                                    {{
                                        $student->date_of_birth
                                            ? \Carbon\Carbon::parse(
                                                $student->date_of_birth
                                            )->format('d/m/Y')
                                            : '—'
                                    }}

                                </td>


                                <td>

                                    {{ $student->gender ?? '—' }}

                                </td>


                                <td>

                                    @if($student->schoolClass)

                                        <span class="badge bg-info text-dark">

                                            {{ $student->schoolClass->class_name }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            Chưa xếp lớp

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($student->status === 'Đang học')

                                        <span class="badge bg-success">

                                            Đang học

                                        </span>

                                    @elseif($student->status === 'Chuyển trường')

                                        <span class="badge bg-warning text-dark">

                                            Chuyển trường

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ $student->status }}

                                        </span>

                                    @endif

                                </td>


                                <td class="text-center">

                                    <a
                                        href="{{ route('bgh.students.show', $student) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Xem chi tiết"
                                    >

                                        <i class="fa-solid fa-eye"></i>

                                        Xem

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="fa-solid fa-user-graduate text-muted"
                                        style="font-size:40px;"
                                    ></i>

                                    <div class="mt-3 text-muted">

                                        Không tìm thấy học sinh phù hợp.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PHÂN TRANG --}}

        @if($students->hasPages())

            <div class="card-footer bg-white">

                {{ $students->links() }}

            </div>

        @endif

    </div>

</div>

@endsection