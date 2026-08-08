@extends('layouts.app')

@section('title', 'Chi tiết thông báo')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Chi tiết thông báo
        </h2>

        <p class="text-muted mb-0">
            Xem nội dung thông báo
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('announcements.edit', $announcement) }}"
           class="btn btn-warning">

            <i class="fa-solid fa-pen me-1"></i>
            Sửa

        </a>

        <a href="{{ route('announcements.index') }}"
           class="btn btn-secondary">

            <i class="fa-solid fa-arrow-left me-1"></i>
            Quay lại

        </a>

    </div>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-primary text-white">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="fa-solid fa-bell me-2"></i>

                {{ $announcement->title }}

            </h5>

            @if($announcement->is_published)

                <span class="badge bg-light text-success">

                    <i class="fa-solid fa-eye me-1"></i>

                    Đang hiển thị

                </span>

            @else

                <span class="badge bg-secondary">

                    <i class="fa-solid fa-eye-slash me-1"></i>

                    Đã ẩn

                </span>

            @endif

        </div>

    </div>


    <div class="card-body">

        <div class="row mb-4">

            <div class="col-md-6">

                <small class="text-muted">
                    Người tạo
                </small>

                <div class="fw-semibold">

                    {{ $announcement->creator->name ?? 'N/A' }}

                </div>

            </div>


            <div class="col-md-6">

                <small class="text-muted">
                    Ngày tạo
                </small>

                <div class="fw-semibold">

                    {{ $announcement->created_at->format('d/m/Y H:i') }}

                </div>

            </div>

        </div>


        <hr>


        <div class="mt-4">

            <h6 class="fw-bold mb-3">
                Nội dung
            </h6>

            <div style="white-space: pre-line; line-height: 1.8;">

                {{ $announcement->content }}

            </div>

        </div>

    </div>

</div>

@endsection