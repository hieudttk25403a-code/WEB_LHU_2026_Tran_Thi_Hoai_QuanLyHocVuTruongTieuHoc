@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                <i class="fas fa-edit"></i>
                Sửa phân công giáo viên
            </h2>

            <p class="text-muted mb-0">
                Cập nhật thông tin giảng dạy
            </p>

        </div>

        <a
            href="{{ route('teacher-subject-assignments.index') }}"
            class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>
            Quay lại

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Có lỗi xảy ra:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-header bg-warning">

            <strong>
                <i class="fas fa-edit"></i>
                Cập nhật phân công
            </strong>

        </div>


        <div class="card-body">

            <form
                action="{{ route(
                    'teacher-subject-assignments.update',
                    $teacherSubjectAssignment
                ) }}"
                method="POST">

                @csrf

                @method('PUT')


                {{-- MÃ GIÁO VIÊN --}}

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">

                            Mã giáo viên
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="teacher_code"
                            id="teacher_code"
                            class="form-control"
                            value="{{ old(
                                'teacher_code',
                                $teacherSubjectAssignment->teacher->teacher_code ?? ''
                            ) }}"
                            required>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">

                            Giáo viên

                        </label>

                        <input
                            type="text"
                            id="teacher_name"
                            class="form-control bg-light"
                            value="{{ $teacherSubjectAssignment->teacher->full_name ?? '' }}"
                            readonly>

                        <div id="teacher_message"
                             class="mt-2">
                        </div>

                    </div>

                </div>


                {{-- MÔN + LỚP --}}

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">

                            Môn học
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="subject_id"
                            id="subject_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Chọn môn --
                            </option>

                            @foreach($subjects as $subject)

                                <option
                                    value="{{ $subject->id }}"
                                    data-subject-name="{{ $subject->subject_name }}"
                                    {{ old(
                                        'subject_id',
                                        $teacherSubjectAssignment->subject_id
                                    ) == $subject->id ? 'selected' : '' }}>

                                    {{ $subject->subject_name }}

                                </option>

                            @endforeach

                        </select>

                        <div
                            id="specialized_message"
                            class="form-text text-primary fw-bold">
                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">

                            Lớp học
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="class_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Chọn lớp --
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ old(
                                        'class_id',
                                        $teacherSubjectAssignment->class_id
                                    ) == $class->id ? 'selected' : '' }}>

                                    {{ $class->class_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- NĂM + THỨ + TIẾT --}}

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">

                            Năm học
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="school_year_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Chọn năm học --
                            </option>

                            @foreach($schoolYears as $schoolYear)

                                <option
                                    value="{{ $schoolYear->id }}"
                                    {{ old(
                                        'school_year_id',
                                        $teacherSubjectAssignment->school_year_id
                                    ) == $schoolYear->id ? 'selected' : '' }}>

                                    {{ $schoolYear->name
                                        ?? $schoolYear->school_year
                                        ?? $schoolYear->year
                                        ?? 'Năm học #' . $schoolYear->id }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">

                            Thứ
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="day_of_week"
                            class="form-select"
                            required>

                            <option value="">
                                -- Chọn --
                            </option>

                            @for($day = 2; $day <= 7; $day++)

                                <option
                                    value="{{ $day }}"
                                    {{ old(
                                        'day_of_week',
                                        $teacherSubjectAssignment->day_of_week
                                    ) == $day ? 'selected' : '' }}>

                                    Thứ {{ $day }}

                                </option>

                            @endfor

                        </select>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">

                            Tiết
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="period"
                            class="form-select"
                            required>

                            <option value="">
                                -- Chọn --
                            </option>

                            @for($period = 1; $period <= 10; $period++)

                                <option
                                    value="{{ $period }}"
                                    {{ old(
                                        'period',
                                        $teacherSubjectAssignment->period
                                    ) == $period ? 'selected' : '' }}>

                                    Tiết {{ $period }}

                                </option>

                            @endfor

                        </select>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route(
                            'teacher-subject-assignments.index'
                        ) }}"
                        class="btn btn-secondary">

                        Hủy

                    </a>

                    <button
                        type="submit"
                        class="btn btn-warning">

                        <i class="fas fa-save"></i>
                        Cập nhật

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const codeInput =
        document.getElementById('teacher_code');

    const nameInput =
        document.getElementById('teacher_name');

    const message =
        document.getElementById('teacher_message');

    const subjectSelect =
        document.getElementById('subject_id');

    const specializedMessage =
        document.getElementById('specialized_message');


    function findTeacher() {

        const code =
            codeInput.value.trim();

        if (!code) {
            return;
        }


        fetch(
            "{{ route('teacher-subject-assignments.find-teacher') }}"
            + "?teacher_code="
            + encodeURIComponent(code)
        )
        .then(response => response.json())
        .then(data => {

            if (!data.success) {

                nameInput.value = '';

                message.innerHTML = `
                    <span class="text-danger">
                        Giáo viên không tồn tại.
                    </span>
                `;

                return;
            }


            nameInput.value =
                data.teacher.full_name;


            message.innerHTML = `
                <span class="text-success">
                    <i class="fas fa-check-circle"></i>
                    Giáo viên hợp lệ.
                </span>
            `;


            if (
                data.is_specialized
                && data.specialized_subject
            ) {

                specializedMessage.innerHTML = `
                    <span class="text-warning">
                        <i class="fas fa-star"></i>
                        Giáo viên chuyên:
                        <strong>
                            ${data.specialized_subject}
                        </strong>
                    </span>
                `;


                let found = false;


                Array.from(
                    subjectSelect.options
                ).forEach(option => {

                    const name =
                        (
                            option.dataset.subjectName
                            || ''
                        ).trim()
                        .toLowerCase();


                    const specialized =
                        data.specialized_subject
                            .trim()
                            .toLowerCase();


                    if (
                        (
                            specialized === 'ngoại ngữ 1'
                            &&
                            (
                                name === 'ngoại ngữ 1'
                                ||
                                name === 'tiếng anh'
                            )
                        )
                        ||
                        (
                            specialized === 'tin học và công nghệ'
                            &&
                            (
                                name === 'tin học và công nghệ'
                                ||
                                name === 'tin học'
                            )
                        )
                    ) {

                        option.selected = true;

                        found = true;

                    }

                });


                subjectSelect.disabled = found;


                if (found) {

                    let hidden =
                        document.getElementById(
                            'specialized_subject_hidden'
                        );


                    if (!hidden) {

                        hidden =
                            document.createElement('input');

                        hidden.type = 'hidden';

                        hidden.name = 'subject_id';

                        hidden.id =
                            'specialized_subject_hidden';

                        subjectSelect
                            .parentNode
                            .appendChild(hidden);

                    }


                    hidden.value =
                        subjectSelect.value;

                }

            }

        });

    }


    codeInput.addEventListener(
        'blur',
        findTeacher
    );

});

</script>

@endsection