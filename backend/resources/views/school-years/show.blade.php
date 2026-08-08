@extends('layouts.app')

@section('title', 'Chi tiết năm học')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Chi tiết năm học
        </h2>

        <p class="text-muted mb-0">
            Thông tin chi tiết của năm học
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('school-years.edit', $schoolYear) }}"
           class="btn btn-warning">

            <i class="fa-solid fa-pen me-1"></i>

            Sửa

        </a>

        <a href="{{ route('school-years.index') }}"
           class="btn btn-secondary">

            <i class="fa-solid fa-arrow-left me-1"></i>

            Quay lại

        </a>

    </div>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-calendar me-2"></i>

            {{ $schoolYear->name }}

        </h5>

    </div>


    <div class="card-body">

        <div class="row">

            {{-- Tên năm học --}}

            <div class="col-md-6 mb-4">

                <label class="text-muted">
                    Tên năm học
                </label>

                <h5 class="fw-semibold">
                    {{ $schoolYear->name }}
                </h5>

            </div>


            {{-- Trạng thái --}}

            <div class="col-md-6 mb-4">

                <label class="text-muted">
                    Trạng thái
                </label>

                <div>

                    @if($schoolYear->is_active)

                        <span class="badge bg-success">

                            <i class="fa-solid fa-circle-check me-1"></i>

                            Đang hoạt động

                        </span>

                    @else

                        <span class="badge bg-secondary">

                            <i class="fa-solid fa-circle-xmark me-1"></i>

                            Đã kết thúc

                        </span>

                    @endif

                </div>

            </div>


            {{-- Ngày bắt đầu --}}

            <div class="col-md-6 mb-4">

                <label class="text-muted">
                    Ngày bắt đầu
                </label>

                <h6>

                    {{ $schoolYear->start_date
                        ? $schoolYear->start_date->format('d/m/Y')
                        : 'Chưa cập nhật' }}

                </h6>

            </div>


            {{-- Ngày kết thúc --}}

            <div class="col-md-6 mb-4">

                <label class="text-muted">
                    Ngày kết thúc
                </label>

                <h6>

                    {{ $schoolYear->end_date
                        ? $schoolYear->end_date->format('d/m/Y')
                        : 'Chưa cập nhật' }}

                </h6>

            </div>

        </div>

    </div>

</div>

@endsection