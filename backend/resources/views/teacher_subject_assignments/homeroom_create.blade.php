@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold">
                Phân công giáo viên chủ nhiệm
            </h2>

            <p class="text-muted mb-0">
                Mỗi giáo viên chỉ được chủ nhiệm một lớp
                trong cùng một năm học.
            </p>
        </div>

        <a
            href="{{ route('teachers.assignment') }}"
            class="btn btn-outline-secondary"
        >
            Quay lại
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('class-assignments.store') }}"
        class="card border-0 shadow-sm"
    >

        @csrf

        <div class="card-body p-4">

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Mã giáo viên *
                    </label>

                    <input
                        type="text"
                        id="teacher_code"
                        name="teacher_code"
                        value="{{ old('teacher_code') }}"
                        class="form-control"
                        placeholder="GV001 / GVCA001 / GVTH001"
                        required
                    >

                    <div
                        id="teacher_result"
                        class="mt-2"
                    ></div>

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Họ và tên
                    </label>

                    <input
                        id="teacher_name"
                        class="form-control bg-light"
                        readonly
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Lớp chủ nhiệm *
                    </label>

                    <select
                        name="class_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Chọn lớp --
                        </option>

                        @foreach($classes as $class)

                            <option
                                value="{{ $class->id }}"
                                @selected(
                                    old('class_id')
                                    == $class->id
                                )
                            >
                                {{ $class->class_name }}
                                - Khối {{ $class->grade }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Năm học *
                    </label>

                    <select
                        name="school_year_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Chọn năm học --
                        </option>

                        @foreach($schoolYears as $year)

                            <option
                                value="{{ $year->id }}"
                                @selected(
                                    old('school_year_id')
                                    == $year->id
                                )
                            >
                                {{ $year->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Môn dạy thêm
                    </label>

                    <select
                        id="subject_id"
                        name="subject_id"
                        class="form-select"
                    >

                        <option value="">
                            -- Không đăng ký môn --
                        </option>

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                data-name="{{ $subject->subject_name }}"
                            >
                                {{ $subject->subject_name }}
                            </option>

                        @endforeach

                    </select>

                    <div
                        id="special_note"
                        class="small text-warning mt-1"
                    ></div>

                </div>


                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Thứ
                    </label>

                    <select
                        name="day_of_week"
                        class="form-select"
                    >

                        <option value="">
                            Không áp dụng
                        </option>

                        @for($day = 2; $day <= 6; $day++)

                            <option value="{{ $day }}">
                                Thứ {{ $day }}
                            </option>

                        @endfor

                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Tiết
                    </label>

                    <select
                        name="period"
                        class="form-select"
                    >

                        <option value="">
                            Không áp dụng
                        </option>

                        @for($period = 1; $period <= 9; $period++)

                            <option value="{{ $period }}">
                                Tiết {{ $period }}
                            </option>

                        @endfor

                    </select>

                </div>

            </div>


            <div class="alert alert-info mt-4">

                Nếu giáo viên không chọn môn dạy:

                <b>chỉ chủ nhiệm → màu xanh dương.</b>

                <br>

                Nếu chọn thêm môn:

                <b>chủ nhiệm + bộ môn → màu đỏ.</b>

                <br>

                Nếu giáo viên là GVCA/GVTH:

                <b>môn sẽ tự động theo mã giáo viên.</b>

            </div>


            <div class="text-end mt-4">

                <button class="btn btn-primary px-4">

                    <i class="fas fa-save me-1"></i>

                    Lưu phân công

                </button>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const code =
            document.getElementById('teacher_code');

        const name =
            document.getElementById('teacher_name');

        const result =
            document.getElementById('teacher_result');

        const subject =
            document.getElementById('subject_id');

        const note =
            document.getElementById('special_note');

        const options =
            Array.from(subject.options);


        async function lookup() {

            const value =
                code.value.trim().toUpperCase();

            if (!value) {
                return;
            }

            const response =
                await fetch(
                    "{{ route('teacher-subject-assignments.teacher-lookup') }}"
                    + "?teacher_code="
                    + encodeURIComponent(value)
                );

            const data =
                await response.json();


            if (!data.found) {

                name.value = '';

                result.innerHTML =
                    '<span class="text-danger">' +
                    data.message +
                    '</span>';

                return;
            }


            name.value =
                data.teacher.name;


            result.innerHTML =
                '<span class="text-success">' +
                '<i class="fas fa-check-circle"></i> ' +
                'Giáo viên hợp lệ' +
                '</span>';


            if (data.teacher.is_specialist) {

                const target =
                    options.find(
                        option =>
                            option.dataset.name
                            === data.teacher.specialized_subject
                    );

                options.forEach(
                    option => {

                        option.disabled =
                            option.value !== ''
                            && option !== target;

                    }
                );


                if (target) {

                    subject.value =
                        target.value;

                    note.textContent =
                        'Môn tự động: '
                        + data.teacher.specialized_subject;

                }

            } else {

                options.forEach(
                    option =>
                        option.disabled = false
                );

                note.textContent =
                    'Giáo viên thường có thể chọn môn dạy.';

            }

        }


        code.addEventListener(
            'blur',
            lookup
        );

    }
);

</script>

@endsection