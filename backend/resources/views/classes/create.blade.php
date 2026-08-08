@extends('layouts.app')

@section('title', 'Thêm lớp học')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Thêm lớp học
        </h2>

        <p class="text-muted mb-0">
            Nhập thông tin lớp học mới
        </p>
    </div>

    <a href="{{ route('classes.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-school me-2"></i>

            Thông tin lớp học

        </h5>

    </div>


    <div class="card-body">

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Vui lòng kiểm tra lại thông tin:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('classes.store') }}"
              method="POST">

            @csrf

            <div class="row">

                {{-- Tên lớp --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Tên lớp <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="class_name"
                        class="form-control"
                        placeholder="Ví dụ: 1A1"
                        value="{{ old('class_name') }}">

                </div>


                {{-- Khối --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Khối <span class="text-danger">*</span>

                    </label>

                    <select name="grade"
                            class="form-select">

                        <option value="">
                            -- Chọn khối --
                        </option>

                        <option value="1"
                            {{ old('grade') == '1' ? 'selected' : '' }}>
                            Khối 1
                        </option>

                        <option value="2"
                            {{ old('grade') == '2' ? 'selected' : '' }}>
                            Khối 2
                        </option>

                        <option value="3"
                            {{ old('grade') == '3' ? 'selected' : '' }}>
                            Khối 3
                        </option>

                        <option value="4"
                            {{ old('grade') == '4' ? 'selected' : '' }}>
                            Khối 4
                        </option>

                        <option value="5"
                            {{ old('grade') == '5' ? 'selected' : '' }}>
                            Khối 5
                        </option>

                    </select>

                </div>


                {{-- Giáo viên chủ nhiệm --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Giáo viên chủ nhiệm

                    </label>

                    <input
                        type="text"
                        name="homeroom_teacher"
                        class="form-control"
                        placeholder="Ví dụ: Nguyễn Thị Lan"
                        value="{{ old('homeroom_teacher') }}">

                </div>


                {{-- Sĩ số --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Sĩ số <span class="text-danger">*</span>

                    </label>

                    <input
                        type="number"
                        name="student_count"
                        class="form-control"
                        min="0"
                        placeholder="Ví dụ: 35"
                        value="{{ old('student_count', 0) }}">

                </div>


                {{-- Trạng thái --}}

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Trạng thái

                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="Đang hoạt động">
                            Đang hoạt động
                        </option>

                        <option value="Đã kết thúc">
                            Đã kết thúc
                        </option>

                    </select>

                </div>

            </div>


            <hr>


            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-success">

                    <i class="fa-solid fa-save me-1"></i>

                    Lưu lớp

                </button>

                <a href="{{ route('classes.index') }}"
                   class="btn btn-secondary">

                    Hủy

                </a>

            </div>

        </form>

    </div>

</div>

@endsection