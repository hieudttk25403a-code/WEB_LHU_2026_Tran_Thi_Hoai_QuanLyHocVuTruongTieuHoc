@extends('layouts.app')

@section('title', 'Cập nhật lớp học')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Cập nhật lớp học
        </h2>

        <p class="text-muted mb-0">
            Chỉnh sửa thông tin lớp {{ $class->class_name }}
        </p>
    </div>

    <a href="{{ route('classes.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-warning">

        <h5 class="mb-0">

            <i class="fa-solid fa-pen me-2"></i>

            Cập nhật thông tin lớp

        </h5>

    </div>


    <div class="card-body">

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>Vui lòng kiểm tra lại:</strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('classes.update', $class) }}"
              method="POST">

            @csrf

            @method('PUT')


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
                        value="{{ old('class_name', $class->class_name) }}">

                </div>


                {{-- Khối --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Khối <span class="text-danger">*</span>

                    </label>

                    <select name="grade"
                            class="form-select">

                        <option value="">-- Chọn khối --</option>

                        @for($i = 1; $i <= 5; $i++)

                            <option value="{{ $i }}"
                                {{ old('grade', $class->grade) == $i ? 'selected' : '' }}>

                                Khối {{ $i }}

                            </option>

                        @endfor

                    </select>

                </div>


                {{-- GVCN --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Giáo viên chủ nhiệm

                    </label>

                    <input
                        type="text"
                        name="homeroom_teacher"
                        class="form-control"
                        value="{{ old('homeroom_teacher', $class->homeroom_teacher) }}">

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
                        value="{{ old('student_count', $class->student_count) }}">

                </div>


                {{-- Trạng thái --}}

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Trạng thái

                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="Đang hoạt động"
                            {{ old('status', $class->status) == 'Đang hoạt động' ? 'selected' : '' }}>

                            Đang hoạt động

                        </option>

                        <option value="Đã kết thúc"
                            {{ old('status', $class->status) == 'Đã kết thúc' ? 'selected' : '' }}>

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

                    Cập nhật

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