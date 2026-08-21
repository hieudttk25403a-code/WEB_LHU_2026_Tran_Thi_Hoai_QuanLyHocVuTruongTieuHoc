@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-user-plus text-success me-2"></i>
                Thêm học sinh
            </h3>

            <p class="text-muted mb-0">
                Thêm hồ sơ học sinh vào hệ thống
            </p>
        </div>

        <a href="{{ route('students.index') }}"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Quay lại
        </a>
    </div>


    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Vui lòng kiểm tra lại:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('students.store') }}"
          method="POST">

        @csrf

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-success text-white">
                <i class="fas fa-user me-2"></i>
                Thông tin cá nhân
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Mã học sinh
                        </label>

                        <input type="text"
                               name="student_code"
                               class="form-control"
                               value="{{ old('student_code') }}"
                               required>
                    </div>


                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Họ và tên
                        </label>

                        <input type="text"
                               name="full_name"
                               class="form-control"
                               value="{{ old('full_name') }}"
                               required>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Ngày sinh
                        </label>

                        <input type="date"
                               name="date_of_birth"
                               class="form-control"
                               value="{{ old('date_of_birth') }}"
                               required>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Giới tính
                        </label>

                        <select name="gender"
                                class="form-select"
                                required>

                            <option value="">
                                -- Chọn giới tính --
                            </option>

                            <option value="Nam"
                                {{ old('gender') == 'Nam' ? 'selected' : '' }}>
                                Nam
                            </option>

                            <option value="Nữ"
                                {{ old('gender') == 'Nữ' ? 'selected' : '' }}>
                                Nữ
                            </option>

                        </select>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Trạng thái
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="Đang học"
                                {{ old('status', 'Đang học') == 'Đang học' ? 'selected' : '' }}>
                                Đang học
                            </option>

                            <option value="Chuyển trường">
                                Chuyển trường
                            </option>

                            <option value="Bảo lưu">
                                Bảo lưu
                            </option>

                            <option value="Thôi học">
                                Thôi học
                            </option>

                            <option value="Đuổi học">
                                Đuổi học
                            </option>

                        </select>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Địa chỉ
                        </label>

                        <textarea name="address"
                                  class="form-control"
                                  rows="2">{{ old('address') }}</textarea>
                    </div>


                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}">
                    </div>


                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Số điện thoại
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone') }}">
                    </div>

                </div>

            </div>
        </div>


        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">
                <i class="fas fa-school me-2"></i>
                Phân lớp
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Năm học
                        </label>

                        <select name="school_year_id"
                                class="form-select">

                            <option value="">
                                -- Chọn năm học --
                            </option>

                            @foreach ($schoolYears as $schoolYear)

                                <option value="{{ $schoolYear->id }}"
                                    {{ old('school_year_id', $schoolYear->is_active ? $schoolYear->id : '') == $schoolYear->id ? 'selected' : '' }}>

                                    {{ $schoolYear->name }}

                                    @if ($schoolYear->is_active)
                                        (Đang diễn ra)
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">
                            Dùng để tạo lịch sử lớp học của học sinh.
                        </small>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Lớp
                        </label>

                        <select name="class_id"
                                class="form-select">

                            <option value="">
                                -- Chọn lớp --
                            </option>

                            @foreach ($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ old('class_id') == $class->id ? 'selected' : '' }}>

                                    Khối {{ $class->grade }}
                                    - {{ $class->class_name }}

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">
                            Khi lưu, lớp hiện tại và lịch sử lớp sẽ được đồng bộ.
                        </small>

                    </div>

                </div>

            </div>
        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('students.index') }}"
               class="btn btn-secondary">

                Hủy

            </a>

            <button type="submit"
                    class="btn btn-success">

                <i class="fas fa-save me-1"></i>
                Lưu học sinh

            </button>

        </div>

    </form>

</div>

@endsection