@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold">
                Phân công giáo viên bộ môn
            </h2>

            <p class="text-muted mb-0">
                Nhập mã giáo viên để hệ thống tự tra cứu.
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
        action="{{ route('teacher-subject-assignments.store') }}"
        class="card border-0 shadow-sm"
    >

        @csrf

        <div class="card-body p-4">

            <div class="row g-3">

                {{-- MÃ GV --}}
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
                        autocomplete="off"
                        required
                    >

                    <div id="teacher_result"
                         class="mt-2">
                    </div>

                </div>


                {{-- TÊN --}}
                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Họ và tên
                    </label>

                    <input
                        type="text"
                        id="teacher_name"
                        class="form-control bg-light"
                        readonly
                    >

                </div>


                {{-- MÔN --}}
                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Môn học *
                    </label>

                    <select
                        id="subject_id"
                        name="subject_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Chọn môn --
                        </option>

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                data-name="{{ $subject->subject_name }}"
                                @selected(
                                    old('subject_id')
                                    == $subject->id
                                )
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


                {{-- LỚP --}}
                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Lớp *
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


                {{-- NĂM HỌC --}}
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


                {{-- THỨ --}}
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Ngày dạy *
                    </label>

                    <select
                        name="day_of_week"
                        class="form-select"
                        required
                    >

                        <option value="2">
                            Thứ 2
                        </option>

                        <option value="3">
                            Thứ 3
                        </option>

                        <option value="4">
                            Thứ 4
                        </option>

                        <option value="5">
                            Thứ 5
                        </option>

                        <option value="6">
                            Thứ 6
                        </option>

                    </select>

                </div>


                {{-- TIẾT --}}
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Tiết *
                    </label>

                    <select
                        name="period"
                        class="form-select"
                        required
                    >

                        @for($i = 1; $i <= 9; $i++)

                            <option value="{{ $i }}">
                                Tiết {{ $i }}
                            </option>

                        @endfor

                    </select>

                </div>


                {{-- TỪ TUẦN --}}
                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Dạy từ ngày *
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        value="{{ old('start_date') }}"
                        class="form-control"
                        required
                    >

                </div>


                {{-- ĐẾN TUẦN --}}
                <div class="col-md-6">

                    <label class="form-label fw-bold">
                        Dạy đến ngày *
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        value="{{ old('end_date') }}"
                        class="form-control"
                        required
                    >

                </div>

            </div>


            <div class="alert alert-info mt-4">

                <i class="fas fa-info-circle me-1"></i>

                <b>Quy tắc:</b>

                <ul class="mb-0 mt-2">

                    <li>
                        GV thường có thể dạy nhiều môn.
                    </li>

                    <li>
                        GVCA chỉ được dạy Ngoại ngữ 1.
                    </li>

                    <li>
                        GVTH/GVCT chỉ được dạy Tin học và Công nghệ.
                    </li>

                    <li>
                        GV thường không được dạy Ngoại ngữ 1/Tin học và Công nghệ.
                    </li>

                    <li>
                        Không cho phép một giáo viên hoặc một lớp
                        có hai tiết trùng nhau.
                    </li>

                    <li>
                        Sau khi lưu, hệ thống tự đồng bộ sang Thời khóa biểu.
                    </li>

                </ul>

            </div>


            <div class="text-end mt-4">

                <button
                    type="submit"
                    class="btn btn-success px-4"
                >
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

        let timer = null;


        async function lookupTeacher() {

            const value =
                code.value.trim().toUpperCase();

            if (!value) {

                name.value = '';

                result.innerHTML = '';

                note.textContent = '';

                options.forEach(
                    option => option.disabled = false
                );

                return;
            }


            try {

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
                        '<i class="fas fa-times-circle"></i> ' +
                        data.message +
                        '</span>';

                    options.forEach(
                        option => option.disabled = false
                    );

                    note.textContent = '';

                    return;
                }


                name.value =
                    data.teacher.name;


                result.innerHTML =
                    '<span class="text-success">' +
                    '<i class="fas fa-check-circle"></i> ' +
                    'Mã giáo viên hợp lệ' +
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
                            'Môn tự động theo mã giáo viên: '
                            + data.teacher.specialized_subject;

                    }

                } else {

                    options.forEach(
                        option =>
                            option.disabled = false
                    );

                    note.textContent =
                        'Giáo viên thường có thể chọn nhiều môn khác nhau.';

                }

            } catch (error) {

                result.innerHTML =
                    '<span class="text-danger">' +
                    'Không thể tra cứu giáo viên.' +
                    '</span>';

            }

        }


        code.addEventListener(
            'input',
            function () {

                clearTimeout(timer);

                timer = setTimeout(
                    lookupTeacher,
                    300
                );

            }
        );


        code.addEventListener(
            'blur',
            lookupTeacher
        );

    }
);

</script>

@endsection