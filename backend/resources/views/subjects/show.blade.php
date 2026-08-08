@extends('layouts.app')

@section('title', 'Chi tiết môn học')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Chi tiết môn học
        </h2>

        <p class="text-muted mb-0">
            Thông tin chi tiết môn {{ $subject->subject_name }}
        </p>
    </div>

    <a href="{{ route('subjects.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-book me-2"></i>

            {{ $subject->subject_name }}

        </h5>

    </div>


    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-4">

                <small class="text-muted">
                    Mã môn
                </small>

                <h5 class="mt-1">
                    {{ $subject->subject_code }}
                </h5>

            </div>


            <div class="col-md-6 mb-4">

                <small class="text-muted">
                    Tên môn
                </small>

                <h5 class="mt-1">
                    {{ $subject->subject_name }}
                </h5>

            </div>


            <div class="col-md-6 mb-4">

                <small class="text-muted">
                    Giáo viên
                </small>

                <h5 class="mt-1">

                    {{ $subject->teacher ?? 'Chưa phân công' }}

                </h5>

            </div>


            <div class="col-md-6 mb-4">

                <small class="text-muted">
                    Khối
                </small>

                <h5 class="mt-1">

                    Khối {{ $subject->grade }}

                </h5>

            </div>


            <div class="col-md-6 mb-4">

                <small class="text-muted">
                    Trạng thái
                </small>

                <div class="mt-1">

                    @if($subject->status == 'Đang giảng dạy')

                        <span class="badge bg-success fs-6">
                            Đang giảng dạy
                        </span>

                    @else

                        <span class="badge bg-secondary fs-6">
                            {{ $subject->status }}
                        </span>

                    @endif

                </div>

            </div>


            <div class="col-md-6 mb-4">

                <small class="text-muted">
                    Ngày tạo
                </small>

                <h5 class="mt-1">

                    {{ $subject->created_at->format('d/m/Y H:i') }}

                </h5>

            </div>

        </div>


        <hr>


        <div class="d-flex gap-2">

            <a href="{{ route('subjects.edit', $subject) }}"
               class="btn btn-warning">

                <i class="fa-solid fa-pen me-1"></i>

                Chỉnh sửa

            </a>

            <a href="{{ route('subjects.index') }}"
               class="btn btn-secondary">

                Quay lại danh sách

            </a>

        </div>

    </div>

</div>

@endsection