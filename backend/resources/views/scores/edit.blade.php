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


{{-- THÔNG BÁO --}}

@if(session('success'))

    <div class="alert alert-success">

        <i class="fa-solid fa-circle-check me-2"></i>

        {{ session('success') }}

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger">

        <i class="fa-solid fa-circle-exclamation me-2"></i>

        {{ session('error') }}

    </div>

@endif


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


<div class="card shadow-sm border-0">

    <div class="card-header bg-warning">

        <h5 class="mb-0">

            <i class="fa-solid fa-pen me-2"></i>

            Cập nhật điểm

        </h5>

    </div>


    <div class="card-body">

        <form action="{{ route('scores.update', $score) }}"
              method="POST">

            @csrf

            @method('PUT')


            {{-- ========================================================= --}}
            {{-- THÔNG TIN CHUNG --}}
            {{-- ========================================================= --}}

            <h5 class="fw-bold mb-3">

                <i class="fa-solid fa-circle-info text-primary me-2"></i>

                Thông tin chung

            </h5>


            <div class="row">

                {{-- HỌC SINH --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">
                        Học sinh
                    </label>

                    <select name="student_id"
                            class="form-select"
                            {{ auth()->user()->isTeacher() ? 'disabled' : '' }}>

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

                    {{-- disabled thì không gửi dữ liệu --}}
                    @if(auth()->user()->isTeacher())

                        <input type="hidden"
                               name="student_id"
                               value="{{ $score->student_id }}">

                    @endif

                </div>


                {{-- MÔN --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">
                        Môn học
                    </label>

                    <select name="subject_id"
                            class="form-select"
                            {{ auth()->user()->isTeacher() ? 'disabled' : '' }}>

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                {{ old('subject_id', $score->subject_id) == $subject->id ? 'selected' : '' }}>

                                {{ $subject->subject_name }}

                            </option>

                        @endforeach

                    </select>

                    @if(auth()->user()->isTeacher())

                        <input type="hidden"
                               name="subject_id"
                               value="{{ $score->subject_id }}">

                    @endif

                </div>


                {{-- NĂM HỌC --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">
                        Năm học
                    </label>

                    <select name="school_year_id"
                            class="form-select"
                            {{ auth()->user()->isTeacher() ? 'disabled' : '' }}>

                        @foreach($schoolYears as $schoolYear)

                            <option
                                value="{{ $schoolYear->id }}"
                                {{ old('school_year_id', $score->school_year_id) == $schoolYear->id ? 'selected' : '' }}>

                                {{ $schoolYear->name }}

                            </option>

                        @endforeach

                    </select>

                    @if(auth()->user()->isTeacher())

                        <input type="hidden"
                               name="school_year_id"
                               value="{{ $score->school_year_id }}">

                    @endif

                </div>

            </div>


            <hr class="my-4">


            {{-- ========================================================= --}}
            {{-- ĐIỂM --}}
            {{-- ========================================================= --}}

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold mb-0">

                    <i class="fa-solid fa-chart-line text-primary me-2"></i>

                    Cập nhật điểm

                </h5>

            </div>


            @php

                $scoreFields = [

                    'oral_score' => [
                        'label' => 'Điểm miệng',
                        'icon' => 'fa-comment',
                    ],

                    'fifteen_minute_score' => [
                        'label' => 'Điểm 15 phút',
                        'icon' => 'fa-clock',
                    ],

                    'midterm_score' => [
                        'label' => 'Điểm giữa kỳ',
                        'icon' => 'fa-book',
                    ],

                    'final_score' => [
                        'label' => 'Điểm cuối kỳ',
                        'icon' => 'fa-graduation-cap',
                    ],

                ];

            @endphp


            <div class="row">


                {{-- ===================================================== --}}
                {{-- ĐIỂM MIỆNG --}}
                {{-- ===================================================== --}}

                @foreach($scoreFields as $field => $info)

                    @php

                        $editCount = $score->editHistories
                            ->where('score_type', $field)
                            ->count();

                        $locked = $editCount >= 3;

                        $oldValue = old(
                            $field,
                            $score->{$field}
                        );

                    @endphp


                    <div class="col-md-3 mb-4">

                        <label class="form-label fw-semibold">

                            <i class="fa-solid {{ $info['icon'] }} me-1"></i>

                            {{ $info['label'] }}

                        </label>


                        <input
                            type="number"
                            name="{{ $field }}"
                            class="form-control
                                {{ $locked ? 'bg-light' : '' }}"
                            min="0"
                            max="10"
                            step="0.1"
                            value="{{ $oldValue }}"
                            {{ $locked ? 'readonly' : '' }}
                        >


                        {{-- TRẠNG THÁI SỬA --}}

                        @if($locked)

                            <div class="mt-2">

                                <span class="badge bg-danger">

                                    <i class="fa-solid fa-lock me-1"></i>

                                    Đã khóa

                                </span>

                            </div>

                            <small class="text-danger d-block mt-1">

                                Đã sửa {{ $editCount }}/3 lần.

                                Giáo viên không thể sửa thêm.

                            </small>

                        @else

                            <div class="mt-2">

                                <span class="badge bg-success">

                                    <i class="fa-solid fa-pen me-1"></i>

                                    Còn {{ 3 - $editCount }} lần sửa

                                </span>

                            </div>

                            <small class="text-muted d-block mt-1">

                                Đã sửa {{ $editCount }}/3 lần.

                            </small>

                        @endif

                    </div>

                @endforeach

            </div>


            {{-- ========================================================= --}}
            {{-- THÔNG BÁO KHÓA --}}
            {{-- ========================================================= --}}

            @php

                $lockedCount = collect(array_keys($scoreFields))
                    ->filter(function ($field) use ($score) {

                        return $score->editHistories
                            ->where('score_type', $field)
                            ->count() >= 3;

                    })
                    ->count();

            @endphp


            @if($lockedCount > 0)

                <div class="alert alert-warning">

                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                    <strong>Lưu ý:</strong>

                    Một hoặc nhiều cột điểm đã được chỉnh sửa đủ
                    3 lần và đã bị khóa.

                    Giáo viên không thể tiếp tục chỉnh sửa các cột này.

                    Nếu cần thay đổi, vui lòng liên hệ
                    <strong>Quản trị viên</strong>.

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- GHI CHÚ --}}
            {{-- ========================================================= --}}

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    <i class="fa-solid fa-note-sticky me-1"></i>

                    Ghi chú

                </label>

                <textarea
                    name="note"
                    class="form-control"
                    rows="3"
                    placeholder="Nhập ghi chú nếu cần...">{{ old('note', $score->note) }}</textarea>

            </div>


            {{-- ========================================================= --}}
            {{-- ĐIỂM TRUNG BÌNH --}}
            {{-- ========================================================= --}}

            <div class="alert alert-info">

                <div class="d-flex align-items-center">

                    <i class="fa-solid fa-calculator fa-lg me-3"></i>

                    <div>

                        <strong>
                            Điểm trung bình hiện tại:
                        </strong>

                        <span class="fw-bold">

                            {{ $score->average_score ?? 'Chưa có' }}

                        </span>

                        <br>

                        <small>

                            Điểm trung bình sẽ được hệ thống
                            tự động tính lại sau khi cập nhật.

                        </small>

                    </div>

                </div>

            </div>


            <hr>


            {{-- ========================================================= --}}
            {{-- NÚT --}}
            {{-- ========================================================= --}}

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