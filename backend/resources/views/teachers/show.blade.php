@extends('layouts.app')

@section('title', 'Chi tiết giáo viên')

@section('content')

<div class="card shadow">

    <div class="card-header bg-info text-white">

        <h4 class="mb-0">
            Chi tiết giáo viên
        </h4>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Mã giáo viên</label>
                <p>{{ $teacher->teacher_code }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Họ và tên</label>
                <p>{{ $teacher->full_name }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Chuyên môn</label>
                <p>{{ $teacher->specialization }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Tổ</label>
                <p>{{ $teacher->department }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Số điện thoại</label>
                <p>{{ $teacher->phone }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Email</label>
                <p>{{ $teacher->email }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Trạng thái</label>

                @if($teacher->status == 'Đang công tác')
                    <span class="badge bg-success">
                        {{ $teacher->status }}
                    </span>
                @else
                    <span class="badge bg-danger">
                        {{ $teacher->status }}
                    </span>
                @endif

            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Ngày tạo</label>
                <p>{{ $teacher->created_at->format('d/m/Y H:i') }}</p>
            </div>

        </div>

        <a href="{{ route('teachers.index') }}"
           class="btn btn-secondary">

            <i class="fa-solid fa-arrow-left"></i>
            Quay lại

        </a>

        <a href="{{ route('teachers.edit', $teacher) }}"
           class="btn btn-warning">

            <i class="fa-solid fa-pen"></i>
            Chỉnh sửa

        </a>

    </div>

</div>

@endsection