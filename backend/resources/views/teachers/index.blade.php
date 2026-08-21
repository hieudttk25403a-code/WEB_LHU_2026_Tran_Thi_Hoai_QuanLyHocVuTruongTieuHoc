@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Quản lý giáo viên
            </h2>

            <p class="text-muted mb-0">
                Danh sách giáo viên trong trường
            </p>
        </div>

        <div class="d-flex gap-2">

            {{-- PHÂN CÔNG GIÁO VIÊN --}}
            <a
                href="{{ route('teachers.assignment') }}"
                class="btn btn-success"
            >
                <i class="fas fa-tasks me-1"></i>
                Phân công giáo viên
            </a>

            {{-- THÊM GIÁO VIÊN --}}
            <a
                href="{{ route('teachers.create') }}"
                class="btn btn-primary"
            >
                <i class="fas fa-plus me-1"></i>
                Thêm giáo viên
            </a>

        </div>

    </div>


    {{-- =========================================================
        CHÚ THÍCH PHÂN LOẠI
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h6 class="fw-bold mb-3">
                <i class="fas fa-info-circle me-1"></i>
                Chú thích phân loại giáo viên
            </h6>

            <div class="d-flex flex-wrap gap-4">

                {{-- ĐỎ --}}
                <span>
                    <i class="fas fa-circle text-danger me-1"></i>

                    <b class="text-danger">
                        Đỏ:
                    </b>

                    Chủ nhiệm + bộ môn thường
                </span>


                {{-- ĐEN --}}
                <span>
                    <i class="fas fa-circle text-dark me-1"></i>

                    <b>
                        Đen:
                    </b>

                    Giáo viên bộ môn thường
                </span>


                {{-- VÀNG --}}
                <span>
                    <i class="fas fa-circle text-warning me-1"></i>

                    <b class="text-warning">
                        Vàng:
                    </b>

                    Giáo viên chuyên Anh/Tin
                </span>


                {{-- XANH DƯƠNG --}}
                <span>
                    <i class="fas fa-circle text-primary me-1"></i>

                    <b class="text-primary">
                        Xanh dương:
                    </b>

                    Chỉ chủ nhiệm
                </span>


                {{-- XÁM --}}
                <span>
                    <i class="fas fa-circle text-secondary me-1"></i>

                    <b class="text-secondary">
                        Xám:
                    </b>

                    Chưa được phân công
                </span>

            </div>


            <div class="small text-muted mt-3">

                <i class="fas fa-star text-warning me-1"></i>

                Giáo viên chuyên đồng thời chủ nhiệm:
                <b>màu vàng + ngôi sao.</b>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TÌM KIẾM
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('teachers.index') }}"
            >

                <div class="row g-2">

                    <div class="col-md-10">

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>

                            <input
                                type="text"
                                name="keyword"
                                value="{{ request('keyword') }}"
                                class="form-control"
                                placeholder="Tìm mã GV, họ tên, chuyên môn, email..."
                            >

                        </div>

                    </div>


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


    {{-- =========================================================
        THÔNG BÁO
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
        DANH SÁCH GIÁO VIÊN
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-3">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Mã GV
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
                                SĐT
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th>
                                Phân loại
                            </th>

                            <th>
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($teachers as $i => $teacher)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | XÁC ĐỊNH MÀU GIÁO VIÊN
                            |--------------------------------------------------------------------------
                            |
                            | teacher_type được xác định từ Controller/Model:
                            |
                            | homeroom_subject = Chủ nhiệm + bộ môn thường
                            | subject          = Bộ môn thường
                            | specialist       = Giáo viên chuyên
                            | homeroom         = Chỉ chủ nhiệm
                            | default          = Chưa phân công
                            |
                            */

                            $color = match($teacher->teacher_type) {

                                'homeroom_subject'
                                    => 'teacher-red',

                                'subject'
                                    => 'teacher-black',

                                'specialist'
                                    => 'teacher-yellow',

                                'homeroom'
                                    => 'teacher-blue',

                                default
                                    => 'teacher-gray',

                            };

                        @endphp


                        <tr>

                            {{-- =================================================
                                STT
                            ================================================== --}}
                            <td>
                                {{ $teachers->firstItem() + $i }}
                            </td>


                            {{-- =================================================
                                MÃ GIÁO VIÊN
                            ================================================== --}}
                            <td>

                                <strong class="{{ $color }}">

                                    {{-- CHỈ HIỆN SAO KHI:
                                         GIÁO VIÊN CHUYÊN + CHỦ NHIỆM
                                    --}}
                                    @if(
                                        $teacher->teacher_type === 'specialist'
                                        && $teacher->has_homeroom
                                    )

                                        <i
                                            class="fas fa-star text-warning me-1"
                                            title="Giáo viên chuyên đồng thời là giáo viên chủ nhiệm"
                                        ></i>

                                    @endif

                                    {{ $teacher->teacher_code }}

                                </strong>

                            </td>


                            {{-- =================================================
                                HỌ VÀ TÊN
                            ================================================== --}}
                            <td>

                                <div class="fw-bold {{ $color }}">

                                    {{ $teacher->full_name }}

                                </div>


                                {{-- -----------------------------------------
                                     GIÁO VIÊN CHUYÊN
                                ------------------------------------------ --}}
                                @if(
                                    $teacher->teacher_type === 'specialist'
                                )

                                    <div class="small text-warning fw-semibold">

                                        {{-- CHỈ HIỆN SAO NẾU ĐỒNG THỜI CHỦ NHIỆM --}}
                                        @if($teacher->has_homeroom)

                                            <i
                                                class="fas fa-star me-1"
                                                title="Đồng thời là giáo viên chủ nhiệm"
                                            ></i>

                                        @endif

                                        {{ $teacher->specialist_subject }}

                                        {{-- Nếu đồng thời chủ nhiệm --}}
                                        @if($teacher->has_homeroom)

                                            <span class="badge bg-primary ms-1">
                                                Chủ nhiệm
                                            </span>

                                        @endif

                                    </div>


                                {{-- -----------------------------------------
                                     CHỦ NHIỆM + BỘ MÔN THƯỜNG
                                ------------------------------------------ --}}
                                @elseif(
                                    $teacher->teacher_type === 'homeroom_subject'
                                )

                                    <div class="small text-danger fw-semibold">

                                        <i class="fas fa-chalkboard-teacher me-1"></i>

                                        Chủ nhiệm + Giáo viên bộ môn

                                    </div>


                                {{-- -----------------------------------------
                                     CHỈ CHỦ NHIỆM
                                ------------------------------------------ --}}
                                @elseif(
                                    $teacher->teacher_type === 'homeroom'
                                )

                                    <div class="small text-primary fw-semibold">

                                        <i class="fas fa-user-tie me-1"></i>

                                        Giáo viên chủ nhiệm

                                    </div>


                                {{-- -----------------------------------------
                                     CHỈ BỘ MÔN THƯỜNG
                                ------------------------------------------ --}}
                                @elseif(
                                    $teacher->teacher_type === 'subject'
                                )

                                    <div class="small text-dark">

                                        <i class="fas fa-book me-1"></i>

                                        Giáo viên bộ môn

                                    </div>


                                {{-- -----------------------------------------
                                     CHƯA PHÂN CÔNG
                                ------------------------------------------ --}}
                                @else

                                    <div class="small text-secondary">

                                        <i class="fas fa-minus-circle me-1"></i>

                                        Chưa được phân công

                                    </div>

                                @endif

                            </td>


                            {{-- =================================================
                                CHUYÊN MÔN
                            ================================================== --}}
                            <td>

                                {{ $teacher->specialization }}

                            </td>


                            {{-- =================================================
                                BỘ PHẬN
                            ================================================== --}}
                            <td>

                                {{ $teacher->department ?? '—' }}

                            </td>


                            {{-- =================================================
                                SỐ ĐIỆN THOẠI
                            ================================================== --}}
                            <td>

                                {{ $teacher->phone ?? '—' }}

                            </td>


                            {{-- =================================================
                                EMAIL
                            ================================================== --}}
                            <td>

                                {{ $teacher->email ?? '—' }}

                            </td>


                            {{-- =================================================
                                TRẠNG THÁI
                            ================================================== --}}
                            <td>

                                @if($teacher->status === 'Đang công tác')

                                    <span class="badge bg-success">
                                        {{ $teacher->status }}
                                    </span>

                                @elseif($teacher->status === 'Nghỉ phép')

                                    <span class="badge bg-warning text-dark">
                                        {{ $teacher->status }}
                                    </span>

                                @elseif($teacher->status === 'Nghỉ làm')

                                    <span class="badge bg-danger">
                                        {{ $teacher->status }}
                                    </span>

                                @elseif(
                                    $teacher->status === 'Nghỉ vì lý do sức khỏe'
                                )

                                    <span class="badge bg-info text-dark">
                                        {{ $teacher->status }}
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $teacher->status }}
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                PHÂN LOẠI
                            ================================================== --}}
                            <td>

                                {{-- =============================================
                                     GIÁO VIÊN CHUYÊN
                                ============================================== --}}
                                @if(
                                    $teacher->teacher_type === 'specialist'
                                )

                                    <span class="badge bg-warning text-dark">

                                        {{-- CHỈ CÓ SAO KHI CHỦ NHIỆM --}}
                                        @if($teacher->has_homeroom)

                                            <i
                                                class="fas fa-star me-1"
                                                title="Giáo viên chuyên + chủ nhiệm"
                                            ></i>

                                        @endif

                                        Giáo viên chuyên

                                    </span>


                                    {{-- Nếu đồng thời chủ nhiệm --}}
                                    @if($teacher->has_homeroom)

                                        <span class="badge bg-primary mt-1">

                                            Chủ nhiệm

                                        </span>

                                    @endif


                                {{-- =============================================
                                     CHỦ NHIỆM + BỘ MÔN THƯỜNG
                                ============================================== --}}
                                @elseif(
                                    $teacher->teacher_type === 'homeroom_subject'
                                )

                                    <span class="badge bg-danger">

                                        <i class="fas fa-user-tie me-1"></i>

                                        Chủ nhiệm + Bộ môn

                                    </span>


                                {{-- =============================================
                                     CHỈ BỘ MÔN
                                ============================================== --}}
                                @elseif(
                                    $teacher->teacher_type === 'subject'
                                )

                                    <span class="badge bg-dark">

                                        <i class="fas fa-book me-1"></i>

                                        Giáo viên bộ môn

                                    </span>


                                {{-- =============================================
                                     CHỈ CHỦ NHIỆM
                                ============================================== --}}
                                @elseif(
                                    $teacher->teacher_type === 'homeroom'
                                )

                                    <span class="badge bg-primary">

                                        <i class="fas fa-user-tie me-1"></i>

                                        Giáo viên chủ nhiệm

                                    </span>


                                {{-- =============================================
                                     CHƯA PHÂN CÔNG
                                ============================================== --}}
                                @else

                                    <span class="badge bg-secondary">

                                        <i class="fas fa-user-slash me-1"></i>

                                        Chưa phân công

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                THAO TÁC
                            ================================================== --}}
                            <td>

                                <div class="d-flex gap-1">

                                    {{-- XEM --}}
                                    <a
                                        href="{{ route('teachers.show', $teacher) }}"
                                        class="btn btn-info btn-sm text-white"
                                        title="Xem chi tiết"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    {{-- SỬA --}}
                                    <a
                                        href="{{ route('teachers.edit', $teacher) }}"
                                        class="btn btn-warning btn-sm"
                                        title="Chỉnh sửa"
                                    >

                                        <i class="fas fa-pen"></i>

                                    </a>


                                    {{-- XÓA --}}
                                    <form
                                        method="POST"
                                        action="{{ route('teachers.destroy', $teacher) }}"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa giáo viên này không?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            title="Xóa giáo viên"
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
                                colspan="10"
                                class="text-center text-muted py-4"
                            >

                                <i class="fas fa-user-slash fa-2x mb-2"></i>

                                <div>
                                    Chưa có giáo viên.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =========================================================
                PHÂN TRANG
            ========================================================== --}}
            <div class="mt-3">

                {{ $teachers->links() }}

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    CSS MÀU GIÁO VIÊN
========================================================== --}}
<style>

    .teacher-red {
        color: #dc3545 !important;
    }

    .teacher-black {
        color: #111 !important;
    }

    .teacher-yellow {
        color: #d39e00 !important;
    }

    .teacher-blue {
        color: #0d6efd !important;
    }

    .teacher-gray {
        color: #6c757d !important;
    }

    .teacher-red,
    .teacher-black,
    .teacher-yellow,
    .teacher-blue,
    .teacher-gray {
        transition: all 0.2s ease;
    }

    .teacher-red:hover,
    .teacher-black:hover,
    .teacher-yellow:hover,
    .teacher-blue:hover,
    .teacher-gray:hover {
        opacity: 0.8;
    }

</style>

@endsection