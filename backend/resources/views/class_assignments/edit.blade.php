@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Chỉnh sửa phân công chủ nhiệm
            </h2>

            <p class="text-muted mb-0">
                Cập nhật giáo viên, lớp hoặc năm học
            </p>
        </div>

        <a
            href="{{ route('class-assignments.index') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
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


    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form
                action="{{ route(
                    'class-assignments.update',
                    $classAssignment
                ) }}"
                method="POST"
            >

                @csrf

                @method('PUT')


                <div class="row g-4">


                    {{-- MÃ GIÁO VIÊN --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Mã giáo viên

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            id="teacher_code"
                            class="form-control"
                            value="{{ old(
                                'teacher_code',
                                $classAssignment->teacher->teacher_code ?? ''
                            ) }}"
                            autocomplete="off"
                        >

                        <input
                            type="hidden"
                            name="teacher_id"
                            id="teacher_id"
                            value="{{ old(
                                'teacher_id',
                                $classAssignment->teacher_id
                            ) }}"
                        >

                        <div
                            id="teacherMessage"
                            class="form-text text-success"
                        >
                            Giáo viên hiện tại đã được chọn.
                        </div>

                    </div>


                    {{-- TÊN --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Giáo viên
                        </label>

                        <div
                            id="teacherInfo"
                            class="form-control bg-light"
                        >

                            <strong>
                                {{ $classAssignment->teacher->full_name ?? 'Không có' }}
                            </strong>

                        </div>

                    </div>


                    {{-- LỚP --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Lớp chủ nhiệm

                            <span class="text-danger">*</span>

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
                                    {{ old(
                                        'class_id',
                                        $classAssignment->class_id
                                    ) == $class->id ? 'selected' : '' }}
                                >

                                    {{ $class->class_name }}

                                    @if(isset($class->grade))
                                        - Khối {{ $class->grade }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- NĂM HỌC --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Năm học

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="school_year_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Chọn năm học --
                            </option>

                            @foreach($schoolYears as $schoolYear)

                                <option
                                    value="{{ $schoolYear->id }}"
                                    {{ old(
                                        'school_year_id',
                                        $classAssignment->school_year_id
                                    ) == $schoolYear->id ? 'selected' : '' }}
                                >

                                    {{ $schoolYear->name
                                        ?? $schoolYear->school_year
                                        ?? (
                                            $schoolYear->start_year
                                            . ' - ' .
                                            $schoolYear->end_year
                                        )
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('class-assignments.index') }}"
                        class="btn btn-light border"
                    >
                        Hủy
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="submitButton"
                    >

                        <i class="fas fa-save me-1"></i>

                        Lưu thay đổi

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const teachers = @json($teachers);

    const codeInput =
        document.getElementById('teacher_code');

    const idInput =
        document.getElementById('teacher_id');

    const info =
        document.getElementById('teacherInfo');

    const message =
        document.getElementById('teacherMessage');

    const button =
        document.getElementById('submitButton');


    function findTeacher()
    {
        const code =
            codeInput.value
                .trim()
                .toUpperCase();


        const teacher =
            teachers.find(function (item) {

                return String(item.teacher_code)
                    .trim()
                    .toUpperCase() === code;

            });


        if (!teacher) {

            idInput.value = '';

            info.innerHTML =
                '<span class="text-danger">' +
                '<i class="fas fa-times-circle me-1"></i>' +
                'Không tìm thấy giáo viên' +
                '</span>';

            message.className =
                'form-text text-danger';

            message.innerText =
                'Mã giáo viên không tồn tại.';

            button.disabled = true;

            return;
        }


        idInput.value = teacher.id;


        info.innerHTML =
            '<strong>' +
            teacher.full_name +
            '</strong> ' +
            '<span class="badge bg-secondary ms-1">' +
            teacher.teacher_code +
            '</span>';


        message.className =
            'form-text text-success';

        message.innerText =
            'Đã tìm thấy giáo viên.';

        button.disabled = false;
    }


    codeInput.addEventListener(
        'input',
        findTeacher
    );

});

</script>

@endsection