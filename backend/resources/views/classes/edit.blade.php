@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-edit me-2 text-warning"></i>

                Chỉnh sửa lớp học

            </h3>

            <p class="text-muted mb-0">

                Cập nhật thông tin lớp
                <strong>{{ $class->class_name }}</strong>

            </p>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('classes.show', $class->id) }}"
               class="btn btn-outline-info">

                <i class="fas fa-eye me-1"></i>

                Xem chi tiết

            </a>


            <a href="{{ route('classes.index') }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>

                Quay lại

            </a>

        </div>

    </div>


    {{-- Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">

                <i class="fas fa-exclamation-circle me-1"></i>

                Có lỗi xảy ra:

            </div>

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">

                Thông tin lớp học

            </h5>

        </div>


        <div class="card-body">

            <form
                action="{{ route('classes.update', $class->id) }}"
                method="POST"
            >

                @csrf

                @method('PUT')


                <div class="row g-4">


                    {{-- Tên lớp --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Tên lớp

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="class_name"
                            class="form-control @error('class_name') is-invalid @enderror"
                            value="{{ old('class_name', $class->class_name) }}"
                            placeholder="Ví dụ: 1A1"
                            required
                        >

                        @error('class_name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Khối --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Khối

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="grade"
                            class="form-select @error('grade') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Chọn khối --
                            </option>

                            @for ($i = 1; $i <= 5; $i++)

                                <option
                                    value="{{ $i }}"
                                    {{ old('grade', $class->grade) == $i ? 'selected' : '' }}
                                >

                                    Khối {{ $i }}

                                </option>

                            @endfor

                        </select>

                        @error('grade')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Sĩ số --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Sĩ số

                        </label>

                        <input
                            type="number"
                            name="student_count"
                            class="form-control @error('student_count') is-invalid @enderror"
                            value="{{ old('student_count', $class->student_count ?? 0) }}"
                            min="0"
                        >

                        <small class="text-muted">

                            Sĩ số có thể được cập nhật tự động từ danh sách học sinh.

                        </small>

                        @error('student_count')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Trạng thái --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Trạng thái

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="Chưa nhập học"
                                {{ old('status', $class->status) == 'Chưa nhập học' ? 'selected' : '' }}>

                                Chưa nhập học

                            </option>

                            <option value="Đang nhập học"
                                {{ old('status', $class->status) == 'Đang nhập học' ? 'selected' : '' }}>

                                Đang nhập học

                            </option>

                            <option value="Đã kết thúc năm học"
                                {{ old('status', $class->status) == 'Đã kết thúc năm học' ? 'selected' : '' }}>

                                Đã kết thúc năm học

                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- GVCN --}}
                    <div class="col-12">

                        <div class="alert alert-warning mb-0">

                            <div class="fw-bold mb-1">

                                <i class="fas fa-user-tie me-1"></i>

                                Giáo viên chủ nhiệm

                            </div>

                            <div>

                                Không chỉnh sửa GVCN tại phần chỉnh sửa lớp.

                                GVCN được quản lý riêng theo từng

                                <strong>năm học</strong>

                                trong chức năng

                                <strong>
                                    Phân công giáo viên chủ nhiệm
                                </strong>.

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('classes.show', $class->id) }}"
                        class="btn btn-secondary"
                    >

                        <i class="fas fa-times me-1"></i>

                        Hủy

                    </a>


                    <button
                        type="submit"
                        class="btn btn-warning"
                    >

                        <i class="fas fa-save me-1"></i>

                        Lưu thay đổi

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection