@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-school me-2 text-primary"></i>
                Thêm lớp học
            </h3>

            <p class="text-muted mb-0">
                Tạo lớp học mới trong hệ thống
            </p>
        </div>

        <a href="{{ route('classes.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Quay lại
        </a>

    </div>


    {{-- Hiển thị lỗi --}}
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

            <h5 class="mb-0 fw-bold">
                Thông tin lớp học
            </h5>

        </div>


        <div class="card-body">

            <form action="{{ route('classes.store') }}"
                  method="POST">

                @csrf


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
                            value="{{ old('class_name') }}"
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
                            value="{{ old('student_count', 0) }}"
                            min="0"
                            placeholder="0"
                        >

                        <small class="text-muted">
                            Sĩ số thực tế sẽ được cập nhật theo danh sách học sinh.
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

                            <option value="">
                                -- Chọn trạng thái --
                            </option>

                            <option value="Chưa nhập học"
                                {{ old('status') == 'Chưa nhập học' ? 'selected' : '' }}>
                                Chưa nhập học
                            </option>

                            <option value="Đang nhập học"
                                {{ old('status', 'Đang nhập học') == 'Đang nhập học' ? 'selected' : '' }}>
                                Đang nhập học
                            </option>

                            <option value="Đã kết thúc năm học"
                                {{ old('status') == 'Đã kết thúc năm học' ? 'selected' : '' }}>
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

                        <div class="alert alert-info mb-0">

                            <div class="fw-bold mb-1">

                                <i class="fas fa-info-circle me-1"></i>

                                Giáo viên chủ nhiệm

                            </div>

                            <div>

                                Giáo viên chủ nhiệm <strong>không nhập tại đây</strong>.

                                Sau khi tạo lớp, bạn vào:

                                <strong>
                                    Phân công giáo viên →
                                    Phân công giáo viên chủ nhiệm
                                </strong>

                                để phân công GVCN theo từng năm học.

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('classes.index') }}"
                       class="btn btn-secondary">

                        <i class="fas fa-times me-1"></i>
                        Hủy

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>
                        Lưu lớp học

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection