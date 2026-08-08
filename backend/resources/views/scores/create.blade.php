@extends('layouts.app')

@section('title', 'Nhập điểm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Nhập điểm
        </h2>

        <p class="text-muted mb-0">
            Nhập kết quả học tập cho học sinh
        </p>
    </div>

    <a href="{{ route('scores.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-pen-to-square me-2"></i>

            Thông tin điểm

        </h5>

    </div>


    <div class="card-body">

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


        <form action="{{ route('scores.store') }}"
              method="POST">

            @csrf


            {{-- THÔNG TIN CHUNG --}}

            <h5 class="fw-bold mb-3">

                <i class="fa-solid fa-circle-info text-primary me-2"></i>

                Thông tin chung

            </h5>


            <div class="row">

                {{-- Học sinh --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">

                        Học sinh <span class="text-danger">*</span>

                    </label>

                    <select name="student_id"
                            class="form-select">

                        <option value="">
                            -- Chọn học sinh --
                        </option>

                        @foreach($students as $student)

                            <option
                                value="{{ $student->id }}"
                                {{ old('student_id') == $student->id ? 'selected' : '' }}>

                                {{ $student->student_code }}
                                -
                                {{ $student->full_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Môn học --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">

                        Môn học <span class="text-danger">*</span>

                    </label>

                    <select name="subject_id"
                            class="form-select">

                        <option value="">
                            -- Chọn môn học --
                        </option>

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                {{ old('subject_id') == $subject->id ? 'selected' : '' }}>

                                {{ $subject->subject_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Năm học --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">

                        Năm học <span class="text-danger">*</span>

                    </label>

                    <select name="school_year_id"
                            class="form-select">

                        <option value="">
                            -- Chọn năm học --
                        </option>

                        @foreach($schoolYears as $schoolYear)

                            <option
                                value="{{ $schoolYear->id }}"
                                {{ old('school_year_id') == $schoolYear->id ? 'selected' : '' }}>

                                {{ $schoolYear->name }}

                                @if($schoolYear->is_active)
                                    (Đang sử dụng)
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            <hr class="my-4">


            {{-- ĐIỂM --}}

            <h5 class="fw-bold mb-3">

                <i class="fa-solid fa-chart-line text-primary me-2"></i>

                Nhập điểm

            </h5>


            <div class="row">

                {{-- Điểm miệng --}}

                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Điểm miệng

                    </label>

                    <input
                        type="number"
                        name="oral_score"
                        class="form-control score-input"
                        min="0"
                        max="10"
                        step="0.1"
                        placeholder="0 - 10"
                        value="{{ old('oral_score') }}">

                </div>


                {{-- Điểm 15 phút --}}

                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Điểm 15 phút

                    </label>

                    <input
                        type="number"
                        name="fifteen_minute_score"
                        class="form-control score-input"
                        min="0"
                        max="10"
                        step="0.1"
                        placeholder="0 - 10"
                        value="{{ old('fifteen_minute_score') }}">

                </div>


                {{-- Giữa kỳ --}}

                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Điểm giữa kỳ

                    </label>

                    <input
                        type="number"
                        name="midterm_score"
                        class="form-control score-input"
                        min="0"
                        max="10"
                        step="0.1"
                        placeholder="0 - 10"
                        value="{{ old('midterm_score') }}">

                </div>


                {{-- Cuối kỳ --}}

                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Điểm cuối kỳ

                    </label>

                    <input
                        type="number"
                        name="final_score"
                        class="form-control score-input"
                        min="0"
                        max="10"
                        step="0.1"
                        placeholder="0 - 10"
                        value="{{ old('final_score') }}">

                </div>

            </div>


            <div class="alert alert-info mt-2">

                <i class="fa-solid fa-circle-info me-2"></i>

                Điểm trung bình và xếp loại sẽ được hệ thống tự động tính
                sau khi lưu.

            </div>


            <hr class="my-4">


            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-success">

                    <i class="fa-solid fa-save me-1"></i>

                    Lưu điểm

                </button>


                <a href="{{ route('scores.index') }}"
                   class="btn btn-secondary">

                    Hủy

                </a>

            </div>

        </form>

    </div>

</div>

@endsection