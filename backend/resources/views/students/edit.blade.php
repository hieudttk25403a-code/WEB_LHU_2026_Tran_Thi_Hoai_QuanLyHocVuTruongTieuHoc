@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-user-edit text-warning me-2"></i>
                Chỉnh sửa học sinh
            </h3>

            <p class="text-muted mb-0">
                {{ $student->full_name }}
            </p>
        </div>

        <a href="{{ route('students.show', $student->id) }}"
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


    <form action="{{ route('students.update', $student->id) }}"
          method="POST">

        @csrf
        @method('PUT')


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
                               value="{{ old('student_code', $student->student_code) }}"
                               required>

                    </div>


                    <div class="col-md-8">

                        <label class="form-label fw-semibold">
                            Họ và tên
                        </label>

                        <input type="text"
                               name="full_name"
                               class="form-control"
                               value="{{ old('full_name', $student->full_name) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Ngày sinh
                        </label>

                        <input type="date"
                               name="date_of_birth"
                               class="form-control"
                               value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Giới tính
                        </label>

                        <select name="gender"
                                class="form-select"
                                required>

                            <option value="Nam"
                                {{ old('gender', $student->gender) == 'Nam' ? 'selected' : '' }}>
                                Nam
                            </option>

                            <option value="Nữ"
                                {{ old('gender', $student->gender) == 'Nữ' ? 'selected' : '' }}>
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

                            @foreach([
                                'Đang học',
                                'Chuyển trường',
                                'Bảo lưu',
                                'Thôi học',
                                'Đuổi học'
                            ] as $status)

                                <option value="{{ $status }}"
                                    {{ old('status', $student->status) == $status ? 'selected' : '' }}>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Địa chỉ
                        </label>

                        <textarea name="address"
                                  class="form-control"
                                  rows="2">{{ old('address', $student->address) }}</textarea>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $student->email) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Số điện thoại
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $student->phone) }}">

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
                                    {{ old('school_year_id') == $schoolYear->id ? 'selected' : '' }}>

                                    {{ $schoolYear->name }}

                                    @if ($schoolYear->is_active)
                                        (Đang diễn ra)
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">
                            Chọn năm học khi chuyển học sinh sang lớp mới.
                        </small>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Lớp hiện tại
                        </label>

                        <select name="class_id"
                                class="form-select">

                            <option value="">
                                -- Chưa phân lớp --
                            </option>

                            @foreach ($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>

                                    Khối {{ $class->grade }}
                                    - {{ $class->class_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('students.show', $student->id) }}"
               class="btn btn-secondary">

                Hủy

            </a>

            <button type="submit"
                    class="btn btn-warning">

                <i class="fas fa-save me-1"></i>
                Cập nhật

            </button>

        </div>

    </form>

</div>

@endsection