@extends('layouts.app')

@section('title', 'Sửa năm học')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">
            Sửa năm học
        </h2>

        <p class="text-muted mb-0">
            Cập nhật thông tin năm học
        </p>

    </div>

    <a href="{{ route('school-years.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-warning">

        <h5 class="mb-0">

            <i class="fa-solid fa-pen me-2"></i>

            Cập nhật năm học

        </h5>

    </div>


    <div class="card-body">

        {{-- Hiển thị lỗi --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Vui lòng kiểm tra lại:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('school-years.update', $schoolYear) }}"
              method="POST">

            @csrf

            @method('PUT')


            <div class="row">

                {{-- Tên năm học --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Tên năm học
                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $schoolYear->name) }}"
                        placeholder="Ví dụ: 2026 - 2027">

                </div>


                {{-- Trạng thái --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Trạng thái

                    </label>

                    <select name="is_active"
                            class="form-select">

                        <option value="1"
                            {{ old('is_active', $schoolYear->is_active) == 1 ? 'selected' : '' }}>

                            Đang hoạt động

                        </option>

                        <option value="0"
                            {{ old('is_active', $schoolYear->is_active) == 0 ? 'selected' : '' }}>

                            Đã kết thúc

                        </option>

                    </select>

                </div>


                {{-- Ngày bắt đầu --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Ngày bắt đầu

                    </label>

                    <input
                        type="date"
                        name="start_date"
                        class="form-control"
                        value="{{ old(
                            'start_date',
                            $schoolYear->start_date
                                ? $schoolYear->start_date->format('Y-m-d')
                                : ''
                        ) }}">

                </div>


                {{-- Ngày kết thúc --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Ngày kết thúc

                    </label>

                    <input
                        type="date"
                        name="end_date"
                        class="form-control"
                        value="{{ old(
                            'end_date',
                            $schoolYear->end_date
                                ? $schoolYear->end_date->format('Y-m-d')
                                : ''
                        ) }}">

                </div>

            </div>


            <hr class="my-4">


            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-success">

                    <i class="fa-solid fa-save me-1"></i>

                    Cập nhật

                </button>


                <a href="{{ route('school-years.index') }}"
                   class="btn btn-secondary">

                    Hủy

                </a>

            </div>

        </form>

    </div>

</div>

@endsection