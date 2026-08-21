@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-user-tie text-primary me-2"></i>
                Phân công giáo viên chủ nhiệm
            </h2>

            <p class="text-muted mb-0">
                Chọn giáo viên, lớp và năm học
            </p>
        </div>

        <a href="{{ route('class-assignments.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Danh sách

        </a>

    </div>


    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form action="{{ route('class-assignments.store') }}"
                  method="POST">

                @csrf

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Giáo viên
                        </label>

                        <select name="teacher_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Chọn giáo viên --
                            </option>

                            @foreach ($teachers as $teacher)

                                <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>

                                    {{ $teacher->teacher_code }}
                                    -
                                    {{ $teacher->full_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Lớp
                        </label>

                        <select name="class_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Chọn lớp --
                            </option>

                            @foreach ($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ old('class_id') == $class->id ? 'selected' : '' }}>

                                    {{ $class->class_name }}
                                    - Khối {{ $class->grade }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Năm học
                        </label>

                        <select name="school_year_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Chọn năm học --
                            </option>

                            @foreach ($schoolYears as $schoolYear)

                                <option value="{{ $schoolYear->id }}"
                                    {{ old('school_year_id') == $schoolYear->id ? 'selected' : '' }}>

                                    {{ $schoolYear->name ?? $schoolYear->school_year ?? $schoolYear->year }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('class-assignments.index') }}"
                       class="btn btn-secondary">

                        Hủy

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>
                        Lưu phân công

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection