@extends('layouts.app')

@section('title', 'Danh sách giáo viên')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
         TIÊU ĐỀ
    ====================================================== --}}

    <div class="mb-4">

        <h2 class="fw-bold mb-1">

            <i class="fa-solid fa-chalkboard-user text-primary me-2"></i>

            Danh sách giáo viên

        </h2>

        <p class="text-muted mb-0">

            Xem danh sách giáo viên đang công tác tại trường

        </p>

    </div>


    {{-- =====================================================
         TÌM KIẾM
    ====================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('bgh.teachers.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- TỪ KHÓA --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Tìm giáo viên

                        </label>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            value="{{ request('keyword') }}"
                            placeholder="Nhập mã, họ tên, chuyên môn hoặc tổ/bộ phận..."
                        >

                    </div>


                    {{-- LOẠI GIÁO VIÊN --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Loại giáo viên

                        </label>

                        <select
                            name="teacher_type"
                            class="form-select"
                        >

                            <option value="">

                                -- Tất cả --

                            </option>

                            <option
                                value="homeroom"
                                {{ request('teacher_type') == 'homeroom' ? 'selected' : '' }}
                            >

                                Giáo viên chủ nhiệm

                            </option>

                            <option
                                value="subject"
                                {{ request('teacher_type') == 'subject' ? 'selected' : '' }}
                            >

                                Giáo viên bộ môn

                            </option>

                            <option
                                value="specialized"
                                {{ request('teacher_type') == 'specialized' ? 'selected' : '' }}
                            >

                                Giáo viên chuyên môn

                            </option>

                            <option
                                value="specialized_homeroom"
                                {{ request('teacher_type') == 'specialized_homeroom' ? 'selected' : '' }}
                            >

                                GV chuyên môn + chủ nhiệm

                            </option>

                        </select>

                    </div>


                    {{-- NÚT --}}

                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fa-solid fa-magnifying-glass me-1"></i>

                                Tìm kiếm

                            </button>


                            <a
                                href="{{ route('bgh.teachers.index') }}"
                                class="btn btn-outline-secondary"
                            >

                                <i class="fa-solid fa-rotate-left"></i>

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

                    Danh sách giáo viên

                </strong>

                <span>

                    Tổng:
                    {{ $teachers->total() }}
                    giáo viên

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
                                Mã giáo viên
                            </th>

                            <th>
                                Họ và tên
                            </th>

                            <th>
                                Chuyên môn
                            </th>

                            <th>
                                Bộ phận
                            </th>

                            <th>
                                Loại giáo viên
                            </th>

                            <th>
                                Số điện thoại
                            </th>

                            <th class="text-center">
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($teachers as $index => $teacher)

                            @php

                                $type = $teacher->teacher_type;

                                $typeText = match($type) {

                                    'homeroom'
                                        => 'Giáo viên chủ nhiệm',

                                    'subject'
                                        => 'Giáo viên bộ môn',

                                    'specialized'
                                        => 'Giáo viên chuyên môn',

                                    'specialized_homeroom'
                                        => 'GV chuyên môn + chủ nhiệm',

                                    default
                                        => $type ?: 'Chưa xác định',

                                };

                            @endphp


                            <tr>

                                {{-- STT --}}

                                <td class="text-center">

                                    {{
                                        ($teachers->currentPage() - 1)
                                        * $teachers->perPage()
                                        + $index
                                        + 1
                                    }}

                                </td>


                                {{-- MÃ --}}

                                <td>

                                    <strong>

                                        {{ $teacher->teacher_code }}

                                    </strong>

                                </td>


                                {{-- HỌ TÊN --}}

                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        @if(!empty($teacher->avatar))

                                            <img
                                                src="{{ asset('storage/' . $teacher->avatar) }}"
                                                width="42"
                                                height="42"
                                                class="rounded-circle"
                                                style="object-fit:cover;"
                                            >

                                        @else

                                            <div
                                                class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                style="
                                                    width:42px;
                                                    height:42px;
                                                "
                                            >

                                                {{
                                                    strtoupper(
                                                        substr(
                                                            $teacher->full_name,
                                                            0,
                                                            1
                                                        )
                                                    )
                                                }}

                                            </div>

                                        @endif


                                        <div>

                                            <div class="fw-semibold">

                                                {{ $teacher->full_name }}

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- CHUYÊN MÔN --}}

                                <td>

                                    {{ $teacher->specialization ?? '—' }}

                                </td>


                                {{-- BỘ PHẬN --}}

                                <td>

                                    {{ $teacher->department ?? '—' }}

                                </td>


                                {{-- LOẠI --}}

                                <td>

                                    @if($type === 'homeroom')

                                        <span class="badge bg-primary">

                                            {{ $typeText }}

                                        </span>

                                    @elseif(
                                        $type === 'subject'
                                        || $type === 'specialized'
                                    )

                                        <span class="badge bg-warning text-dark">

                                            {{ $typeText }}

                                        </span>

                                    @elseif(
                                        $type === 'specialized_homeroom'
                                    )

                                        <span class="badge bg-success">

                                            {{ $typeText }}

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ $typeText }}

                                        </span>

                                    @endif

                                </td>


                                {{-- ĐIỆN THOẠI --}}

                                <td>

                                    {{ $teacher->phone ?? '—' }}

                                </td>


                                {{-- XEM --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route('bgh.teachers.show', $teacher) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Xem chi tiết"
                                    >

                                        <i class="fa-solid fa-eye me-1"></i>

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
                                        class="fa-solid fa-chalkboard-user text-muted"
                                        style="font-size:42px;"
                                    ></i>

                                    <p class="text-muted mt-3 mb-0">

                                        Không tìm thấy giáo viên.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PHÂN TRANG --}}

        @if($teachers->hasPages())

            <div class="card-footer bg-white">

                {{ $teachers->links() }}

            </div>

        @endif

    </div>

</div>

@endsection