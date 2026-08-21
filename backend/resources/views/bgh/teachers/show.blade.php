@extends('layouts.app')

@section('title', 'Chi tiết giáo viên')

@section('content')

<div class="container-fluid">

    {{-- QUAY LẠI --}}

    <div class="mb-3">

        <a
            href="{{ route('bgh.teachers.index') }}"
            class="btn btn-outline-secondary"
        >

            <i class="fa-solid fa-arrow-left me-1"></i>

            Quay lại danh sách

        </a>

    </div>


    {{-- =====================================================
         THÔNG TIN GIÁO VIÊN
    ====================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="fa-solid fa-chalkboard-user me-2"></i>

                Thông tin giáo viên

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4 align-items-center">


                {{-- AVATAR --}}

                <div class="col-md-3 text-center">

                    @if(!empty($teacher->avatar))

                        <img
                            src="{{ asset('storage/' . $teacher->avatar) }}"
                            class="rounded-circle shadow-sm"
                            width="130"
                            height="130"
                            style="object-fit:cover;"
                        >

                    @else

                        <div
                            class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                            style="
                                width:130px;
                                height:130px;
                                font-size:50px;
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

                </div>


                {{-- THÔNG TIN --}}

                <div class="col-md-9">

                    <div class="row g-4">


                        {{-- MÃ --}}

                        <div class="col-md-4">

                            <div class="text-muted small">

                                Mã giáo viên

                            </div>

                            <div class="fw-bold fs-5">

                                {{ $teacher->teacher_code }}

                            </div>

                        </div>


                        {{-- HỌ TÊN --}}

                        <div class="col-md-4">

                            <div class="text-muted small">

                                Họ và tên

                            </div>

                            <div class="fw-bold fs-5">

                                {{ $teacher->full_name }}

                            </div>

                        </div>


                        {{-- GIỚI TÍNH --}}

                        <div class="col-md-4">

                            <div class="text-muted small">

                                Giới tính

                            </div>

                            <div class="fw-semibold">

                                {{ $teacher->gender ?? '—' }}

                            </div>

                        </div>


                        {{-- CHUYÊN MÔN --}}

                        <div class="col-md-4">

                            <div class="text-muted small">

                                Chuyên môn

                            </div>

                            <div class="fw-semibold">

                                {{ $teacher->specialization ?? '—' }}

                            </div>

                        </div>


                        {{-- BỘ PHẬN --}}

                        <div class="col-md-4">

                            <div class="text-muted small">

                                Bộ phận / tổ

                            </div>

                            <div class="fw-semibold">

                                {{ $teacher->department ?? '—' }}

                            </div>

                        </div>


                        {{-- LOẠI GV --}}

                        <div class="col-md-4">

                            <div class="text-muted small">

                                Loại giáo viên

                            </div>

                            <div class="fw-semibold">

                                @php

                                    $typeText = match(
                                        $teacher->teacher_type
                                    ) {

                                        'homeroom'
                                            => 'Giáo viên chủ nhiệm',

                                        'subject'
                                            => 'Giáo viên bộ môn',

                                        'specialized'
                                            => 'Giáo viên chuyên môn',

                                        'specialized_homeroom'
                                            => 'GV chuyên môn + chủ nhiệm',

                                        default
                                            => $teacher->teacher_type
                                                ?: 'Chưa xác định',

                                    };

                                @endphp

                                {{ $typeText }}

                            </div>

                        </div>


                        {{-- SĐT --}}

                        <div class="col-md-4">

                            <div class="text-muted small">

                                Số điện thoại

                            </div>

                            <div class="fw-semibold">

                                {{ $teacher->phone ?? '—' }}

                            </div>

                        </div>


                        {{-- EMAIL --}}

                        <div class="col-md-4">

                            <div class="text-muted small">

                                Email

                            </div>

                            <div class="fw-semibold">

                                {{ $teacher->email ?? '—' }}

                            </div>

                        </div>


                        {{-- ĐỊA CHỈ --}}

                        <div class="col-md-4">

                            <div class="text-muted small">

                                Địa chỉ

                            </div>

                            <div class="fw-semibold">

                                {{ $teacher->address ?? '—' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         LƯU Ý QUYỀN
    ====================================================== --}}

    <div class="alert alert-info">

        <i class="fa-solid fa-circle-info me-2"></i>

        Ban Giám Hiệu đang ở chế độ

        <strong>chỉ xem</strong> thông tin giáo viên.

        Các chức năng thêm, sửa và xóa giáo viên
        không được hiển thị tại đây.

    </div>

</div>

@endsection