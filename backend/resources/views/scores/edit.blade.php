@extends('layouts.app')

@section('title', 'Sửa điểm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Sửa điểm
        </h2>

        <p class="text-muted mb-0">
            Cập nhật kết quả học tập
        </p>
    </div>

    <a href="{{ route('scores.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-warning">

        <h5 class="mb-0">

            <i class="fa-solid fa-pen me-2"></i>

            Cập nhật điểm

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


        <form action="{{ route('scores.update', $score) }}"
              method="POST">

            @csrf

            @method('PUT')


            {{-- THÔNG TIN CHUNG --}}

            <h5 class="fw-bold mb-3">

                <i class="fa-solid fa-circle-info text-primary me-2"></i>

                Thông tin chung

            </h5>


            <div class="row">

                {{-- Học sinh --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">

                        Học sinh

                    </label>

                    <select name="student_id"
                            class="form-select">

                        @foreach($students as $student)

                            <option
                                value="{{ $student->id }}"
                                {{ old('student_id', $score->student_id) == $student->id ? 'selected' : '' }}>

                                {{ $student->student_code }}
                                -
                                {{ $student->full_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Môn --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">

                        Môn học

                    </label>

                    <select name="subject_id"
                            class="form-select">

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                {{ old('subject_id', $score->subject_id) == $subject->id ? 'selected' : '' }}>

                                {{ $subject->subject_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Năm học --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">

                        Năm học

                    </label>

                    <select name="school_year_id"
                            class="form-select">

                        @foreach($schoolYears as $schoolYear)

                            <option
                                value="{{ $schoolYear->id }}"
                                {{ old('school_year_id', $score->school_year_id) == $schoolYear->id ? 'selected' : '' }}>

                                {{ $schoolYear->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            <hr class="my-4">


            {{-- ĐIỂM --}}

            <h5 class="fw-bold mb-3">

                <i class="fa-solid fa-chart-line text-primary me-2"></i>

                Cập nhật điểm

            </h5>


            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Điểm miệng

                    </label>

                    <input
                        type="number"
                        name="oral_score"
                        class="form-control"
                        min="0"
                        max="10"
                        step="0.1"
                        value="{{ old('oral_score', $score->oral_score) }}">

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Điểm 15 phút

                    </label>

                    <input
                        type="number"
                        name="fifteen_minute_score"
                        class="form-control"
                        min="0"
                        max="10"
                        step="0.1"
                        value="{{ old('fifteen_minute_score', $score->fifteen_minute_score) }}">

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Điểm giữa kỳ

                    </label>

                    <input
                        type="number"
                        name="midterm_score"
                        class="form-control"
                        min="0"
                        max="10"
                        step="0.1"
                        value="{{ old('midterm_score', $score->midterm_score) }}">

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label fw-semibold">

                        Điểm cuối kỳ

                    </label>

                    <input
                        type="number"
                        name="final_score"
                        class="form-control"
                        min="0"
                        max="10"
                        step="0.1"
                        value="{{ old('final_score', $score->final_score) }}">

                </div>

            </div>


            <div class="alert alert-info">

                <i class="fa-solid fa-calculator me-2"></i>

                Điểm trung bình và xếp loại sẽ được hệ thống
                tự động tính lại sau khi cập nhật.

            </div>


            <hr>


            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-success">

                    <i class="fa-solid fa-save me-1"></i>

                    Cập nhật điểm

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