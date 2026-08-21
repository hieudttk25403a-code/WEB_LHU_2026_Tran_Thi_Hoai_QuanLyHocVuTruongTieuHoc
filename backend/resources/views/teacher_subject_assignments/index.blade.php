@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold">
                Phân công giáo viên bộ môn
            </h2>

            <p class="text-muted mb-0">
                Quản lý giáo viên, môn học, lớp và lịch dạy
            </p>
        </div>

        <a
            href="{{ route('teacher-subject-assignments.create') }}"
            class="btn btn-success"
        >
            <i class="fas fa-plus"></i>
            Phân công giáo viên
        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('teacher-subject-assignments.index') }}"
                class="row g-3 mb-4"
            >

                <div class="col-md-4">

                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Mã GV, tên GV hoặc môn..."
                        value="{{ request('keyword') }}"
                    >

                </div>


                <div class="col-md-2">

                    <select
                        name="class_id"
                        class="form-select"
                    >

                        <option value="">
                            -- Lớp --
                        </option>

                        @foreach($classes as $class)

                            <option
                                value="{{ $class->id }}"
                                @selected(
                                    request('class_id')
                                    == $class->id
                                )
                            >
                                {{ $class->class_name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3">

                    <select
                        name="subject_id"
                        class="form-select"
                    >

                        <option value="">
                            -- Môn --
                        </option>

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                @selected(
                                    request('subject_id')
                                    == $subject->id
                                )
                            >
                                {{ $subject->subject_name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3">

                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        <i class="fas fa-search"></i>
                        Tìm kiếm
                    </button>

                    <a
                        href="{{ route('teacher-subject-assignments.index') }}"
                        class="btn btn-secondary"
                    >
                        Xóa lọc
                    </a>

                </div>

            </form>


            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-success">

                        <tr>

                            <th>#</th>

                            <th>Mã GV</th>

                            <th>Giáo viên</th>

                            <th>Môn</th>

                            <th>Lớp</th>

                            <th>Năm học</th>

                            <th class="text-center">
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($assignments as $assignment)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>
                                    <strong>
                                        {{ $assignment->teacher->teacher_code }}
                                    </strong>
                                </td>


                                <td>

                                    {{ $assignment->teacher->full_name }}

                                    @if($assignment->teacher->isSpecialist())

                                        <span
                                            class="badge bg-warning text-dark"
                                        >
                                            Chuyên
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $assignment->subject->subject_name }}
                                </td>


                                <td>
                                    {{ $assignment->schoolClass->class_name }}
                                </td>


                                <td>
                                    {{ $assignment->schoolYear->year_name ?? '' }}
                                </td>


                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'teacher-subject-assignments.show',
                                            $assignment
                                        ) }}"
                                        class="btn btn-sm btn-info"
                                    >
                                        Xem
                                    </a>


                                    <a
                                        href="{{ route(
                                            'teacher-subject-assignments.edit',
                                            $assignment
                                        ) }}"
                                        class="btn btn-sm btn-warning"
                                    >
                                        Sửa
                                    </a>


                                    <form
                                        action="{{ route(
                                            'teacher-subject-assignments.destroy',
                                            $assignment
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa phân công này?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-danger"
                                        >
                                            Xóa
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center text-muted py-4"
                                >
                                    Chưa có phân công giáo viên bộ môn.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $assignments->links() }}

            </div>

        </div>

    </div>

</div>

@endsection