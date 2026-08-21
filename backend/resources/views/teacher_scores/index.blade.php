@extends('layouts.app')

@section('title', 'Quản lý điểm số')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Quản lý điểm số
            </h2>

            <p class="text-muted mb-0">
                Giáo viên: {{ $teacher->full_name }}
            </p>
        </div>

    </div>


    {{-- Năm học --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('teacher.scores.index') }}"
                class="row g-3 align-items-end"
            >

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Năm học
                    </label>

                    <select
                        name="school_year_id"
                        class="form-select"
                    >

                        @foreach($schoolYears as $year)

                            <option
                                value="{{ $year->id }}"
                                {{ $schoolYearId == $year->id ? 'selected' : '' }}
                            >
                                {{ $year->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-success w-100">

                        <i class="fas fa-search me-1"></i>

                        Xem

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- Lớp chủ nhiệm --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="fw-bold mb-0">

                <i class="fas fa-user-tie text-success me-2"></i>

                Lớp chủ nhiệm

            </h5>

        </div>

        <div class="card-body">

            @forelse($homeroomAssignments as $assignment)

                @if($assignment->schoolClass)

                    <a
                        href="{{ route(
                            'teacher.scores.class',
                            [
                                'class' => $assignment->schoolClass->id,
                                'school_year_id' => $schoolYearId
                            ]
                        ) }}"
                        class="btn btn-outline-success me-2 mb-2"
                    >

                        {{ $assignment->schoolClass->class_name }}

                    </a>

                @endif

            @empty

                <p class="text-muted mb-0">
                    Bạn chưa được phân công chủ nhiệm lớp nào.
                </p>

            @endforelse

        </div>

    </div>


    {{-- Lớp bộ môn --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="fw-bold mb-0">

                <i class="fas fa-book text-primary me-2"></i>

                Lớp giảng dạy bộ môn

            </h5>

        </div>

        <div class="card-body">

            @forelse($subjectAssignments as $assignment)

                @if($assignment->schoolClass)

                    <a
                        href="{{ route(
                            'teacher.scores.class',
                            [
                                'class' => $assignment->schoolClass->id,
                                'school_year_id' => $schoolYearId
                            ]
                        ) }}"
                        class="btn btn-outline-primary me-2 mb-2"
                    >

                        {{ $assignment->schoolClass->class_name }}

                        @if($assignment->subject)

                            -

                            {{ $assignment->subject->subject_name }}

                        @endif

                    </a>

                @endif

            @empty

                <p class="text-muted mb-0">

                    Bạn chưa được phân công môn học nào.

                </p>

            @endforelse

        </div>

    </div>

</div>

@endsection