@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">

        <h2 class="fw-bold">
            Chi tiết phân công
        </h2>

        <a
            href="{{ route('teacher-subject-assignments.index') }}"
            class="btn btn-secondary"
        >
            Quay lại
        </a>

    </div>


    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <p>
                        <strong>Mã giáo viên:</strong>
                        {{ $teacherSubjectAssignment->teacher->teacher_code }}
                    </p>

                    <p>
                        <strong>Giáo viên:</strong>
                        {{ $teacherSubjectAssignment->teacher->full_name }}
                    </p>

                    <p>
                        <strong>Môn:</strong>
                        {{ $teacherSubjectAssignment->subject->subject_name }}
                    </p>

                </div>


                <div class="col-md-6">

                    <p>
                        <strong>Lớp:</strong>
                        {{ $teacherSubjectAssignment->schoolClass->class_name }}
                    </p>

                    <p>
                        <strong>Năm học:</strong>
                        {{ $teacherSubjectAssignment->schoolYear->year_name ?? '' }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    <div class="card shadow-sm">

        <div class="card-header bg-success text-white">

            <strong>
                Lịch dạy
            </strong>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Thứ</th>
                            <th>Bắt đầu</th>
                            <th>Kết thúc</th>
                            <th>Phòng</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($timetables as $timetable)

                            <tr>

                                <td>
                                    {{ $timetable->day_of_week }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse(
                                        $timetable->start_time
                                    )->format('H:i') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse(
                                        $timetable->end_time
                                    )->format('H:i') }}
                                </td>

                                <td>
                                    {{ $timetable->room ?? '---' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted"
                                >
                                    Chưa có lịch dạy.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection