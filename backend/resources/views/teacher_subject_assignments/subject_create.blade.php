@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-chalkboard-teacher text-success"></i>
                Phân công giáo viên bộ môn
            </h3>

            <div class="text-muted">
                Phân công giáo viên → môn học → lớp → thứ → năm học
            </div>
        </div>

        <a href="{{ route('teacher-assignments.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left"></i>
            Quay lại

        </a>

    </div>


    {{-- ERRORS --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- FORM --}}
    <div class="card shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('teacher-subject-assignments.store') }}"
                method="POST"
            >

                @csrf


                <div class="row g-3">


                    {{-- GIÁO VIÊN --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Giáo viên <span class="text-danger">*</span>
                        </label>

                        <select
                            name="teacher_id"
                            id="teacher_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Chọn giáo viên --
                            </option>

                            @foreach ($teachers as $teacher)

                                <option
                                    value="{{ $teacher->id }}"
                                    data-code="{{ $teacher->teacher_code }}"
                                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}
                                >

                                    {{ $teacher->teacher_code }}
                                    -
                                    {{ $teacher->full_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- MÔN HỌC --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Môn học <span class="text-danger">*</span>
                        </label>

                        <select
                            name="subject_id"
                            id="subject_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Chọn môn học --
                            </option>

                            @foreach ($subjects as $subject)

                                <option
                                    value="{{ $subject->id }}"
                                    data-name="{{ $subject->subject_name }}"
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}
                                >

                                    {{ $subject->subject_name }}

                                </option>

                            @endforeach

                        </select>

                        <small
                            id="specialSubjectMessage"
                            class="text-primary d-block mt-1"
                        ></small>

                    </div>


                    {{-- LỚP --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Lớp <span class="text-danger">*</span>
                        </label>

                        <select
                            name="class_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Chọn lớp --
                            </option>

                            @foreach ($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ old('class_id') == $class->id ? 'selected' : '' }}
                                >

                                    {{ $class->class_name }}

                                    - Khối {{ $class->grade }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- NĂM HỌC --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Năm học <span class="text-danger">*</span>
                        </label>

                        <select
                            name="school_year_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Chọn năm học --
                            </option>

                            @foreach ($schoolYears as $year)

                                <option
                                    value="{{ $year->id }}"
                                    {{ old('school_year_id') == $year->id ? 'selected' : '' }}
                                >

                                    {{ $year->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- THỨ --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Thứ <span class="text-danger">*</span>
                        </label>

                        <select
                            name="day_of_week"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Chọn thứ --
                            </option>

                            <option
                                value="1"
                                {{ old('day_of_week') == '1' ? 'selected' : '' }}
                            >
                                Thứ 2
                            </option>

                            <option
                                value="2"
                                {{ old('day_of_week') == '2' ? 'selected' : '' }}
                            >
                                Thứ 3
                            </option>

                            <option
                                value="3"
                                {{ old('day_of_week') == '3' ? 'selected' : '' }}
                            >
                                Thứ 4
                            </option>

                            <option
                                value="4"
                                {{ old('day_of_week') == '4' ? 'selected' : '' }}
                            >
                                Thứ 5
                            </option>

                            <option
                                value="5"
                                {{ old('day_of_week') == '5' ? 'selected' : '' }}
                            >
                                Thứ 6
                            </option>

                            <option
                                value="6"
                                {{ old('day_of_week') == '6' ? 'selected' : '' }}
                            >
                                Thứ 7
                            </option>

                            <option
                                value="7"
                                {{ old('day_of_week') == '7' ? 'selected' : '' }}
                            >
                                Chủ nhật
                            </option>

                        </select>

                        <small class="text-muted">
                            Chọn ngày giáo viên được phân công dạy lớp này.
                        </small>

                    </div>


                    {{-- GHI CHÚ --}}
                    <div class="col-12">

                        <label class="form-label fw-bold">
                            Ghi chú
                        </label>

                        <textarea
                            name="note"
                            class="form-control"
                            rows="3"
                            placeholder="Ghi chú nếu có..."
                        >{{ old('note') }}</textarea>

                    </div>

                </div>


                <hr class="my-4">


                {{-- LƯU --}}
                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('teacher-assignments.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="fas fa-times"></i>
                        Hủy
                    </a>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="fas fa-save"></i>

                        Lưu phân công

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const teacherSelect =
            document.getElementById('teacher_id');

        const subjectSelect =
            document.getElementById('subject_id');

        const message =
            document.getElementById(
                'specialSubjectMessage'
            );


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA GIÁO VIÊN CHUYÊN
        |--------------------------------------------------------------------------
        */

        function checkTeacher()
        {
            const option =
                teacherSelect.options[
                    teacherSelect.selectedIndex
                ];


            if (!option) {
                return;
            }


            const code =
                option.dataset.code || '';


            /*
            |--------------------------------------------------------------------------
            | GVCA -> Tiếng Anh
            |--------------------------------------------------------------------------
            */

            if (
                code.toUpperCase()
                    .startsWith('GVCA')
            ) {

                message.innerText =
                    'Giáo viên chuyên Anh: hệ thống yêu cầu môn Tiếng Anh.';

                setSpecialSubject(
                    'Tiếng Anh'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | GVTH / GVCT -> Tin học
            |--------------------------------------------------------------------------
            */

            if (
                code.toUpperCase()
                    .startsWith('GVTH')
                ||
                code.toUpperCase()
                    .startsWith('GVCT')
            ) {

                message.innerText =
                    'Giáo viên chuyên Tin: hệ thống yêu cầu môn Tin học.';

                setSpecialSubject(
                    'Tin học'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Giáo viên thường
            |--------------------------------------------------------------------------
            */

            message.innerText = '';

            subjectSelect.disabled = false;
        }


        /*
        |--------------------------------------------------------------------------
        | TỰ ĐỘNG CHỌN MÔN CHUYÊN
        |--------------------------------------------------------------------------
        */

        function setSpecialSubject(
            subjectName
        ) {

            let found = false;


            Array.from(
                subjectSelect.options
            ).forEach(function (option) {

                const name =
                    option.dataset.name || '';


                if (
                    name.trim().toLowerCase()
                    ===
                    subjectName.trim().toLowerCase()
                ) {

                    option.selected = true;

                    found = true;

                }

            });


            /*
            | Không cho đổi môn đối với giáo viên chuyên.
            */

            subjectSelect.disabled = true;


            if (!found) {

                message.innerText +=
                    ' Chưa tìm thấy môn này trong danh sách môn học.';

            }

        }


        teacherSelect.addEventListener(
            'change',
            checkTeacher
        );


        checkTeacher();

    });

</script>

@endsection