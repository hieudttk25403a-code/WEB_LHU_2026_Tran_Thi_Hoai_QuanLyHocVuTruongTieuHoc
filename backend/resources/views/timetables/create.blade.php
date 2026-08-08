@extends('layouts.app')

@section('title', 'Thêm thời khóa biểu')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Thêm thời khóa biểu
        </h2>

        <p class="text-muted mb-0">
            Tạo lịch học mới
        </p>
    </div>

    <a href="{{ route('timetables.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-calendar-plus me-2"></i>

            Thông tin thời khóa biểu

        </h5>

    </div>


    <div class="card-body">

        {{-- Hiển thị lỗi --}}

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


        <form action="{{ route('timetables.store') }}"
              method="POST">

            @csrf


            <div class="row">

                {{-- LỚP --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Lớp <span class="text-danger">*</span>

                    </label>

                    <select name="class_id"
                            class="form-select">

                        <option value="">
                            -- Chọn lớp --
                        </option>

                        @foreach($classes as $class)

                            <option value="{{ $class->id }}"
                                {{ old('class_id') == $class->id ? 'selected' : '' }}>

                                {{ $class->class_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- MÔN HỌC --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Môn học <span class="text-danger">*</span>

                    </label>

                    <select name="subject_id"
                            class="form-select">

                        <option value="">
                            -- Chọn môn học --
                        </option>

                        @foreach($subjects as $subject)

                            <option value="{{ $subject->id }}"
                                {{ old('subject_id') == $subject->id ? 'selected' : '' }}>

                                {{ $subject->subject_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- GIÁO VIÊN --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Giáo viên <span class="text-danger">*</span>

                    </label>

                    <select name="teacher_id"
                            class="form-select">

                        <option value="">
                            -- Chọn giáo viên --
                        </option>

                        @foreach($teachers as $teacher)

                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>

                                {{ $teacher->full_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- NĂM HỌC --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Năm học <span class="text-danger">*</span>

                    </label>

                    <select name="school_year_id"
                            class="form-select">

                        <option value="">
                            -- Chọn năm học --
                        </option>

                        @foreach($schoolYears as $schoolYear)

                            <option value="{{ $schoolYear->id }}"
                                {{ old('school_year_id') == $schoolYear->id ? 'selected' : '' }}>

                                {{ $schoolYear->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- THỨ --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Thứ <span class="text-danger">*</span>

                    </label>

                    <select name="day_of_week"
                            class="form-select">

                        <option value="">
                            -- Chọn thứ --
                        </option>

                        @foreach([
                            'Thứ 2',
                            'Thứ 3',
                            'Thứ 4',
                            'Thứ 5',
                            'Thứ 6',
                            'Thứ 7'
                        ] as $day)

                            <option value="{{ $day }}"
                                {{ old('day_of_week') == $day ? 'selected' : '' }}>

                                {{ $day }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- GIỜ BẮT ĐẦU --}}

                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Giờ bắt đầu
                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="time"
                        name="start_time"
                        class="form-control"
                        value="{{ old('start_time') }}">

                </div>


                {{-- GIỜ KẾT THÚC --}}

                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Giờ kết thúc
                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="time"
                        name="end_time"
                        class="form-control"
                        value="{{ old('end_time') }}">

                </div>


                {{-- PHÒNG --}}

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Phòng học

                    </label>

                    <input
                        type="text"
                        name="room"
                        class="form-control"
                        placeholder="Ví dụ: Phòng 101"
                        value="{{ old('room') }}">

                </div>

            </div>


            <hr>


            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-success">

                    <i class="fa-solid fa-save me-1"></i>

                    Lưu thời khóa biểu

                </button>


                <a href="{{ route('timetables.index') }}"
                   class="btn btn-secondary">

                    Hủy

                </a>

            </div>

        </form>

    </div>

</div>

@endsection