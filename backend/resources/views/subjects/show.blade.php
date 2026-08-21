@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                {{ $subject->subject_name }}
            </h2>

            <p class="text-muted mb-0">

                Mã môn:
                <strong>
                    {{ $subject->subject_code }}
                </strong>

                |
                Khối:
                {{ $subject->grade }}

            </p>

        </div>

        <a
            href="{{ route('subjects.index') }}"
            class="btn btn-outline-secondary"
        >
            Quay lại
        </a>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-3">

                <i class="fas fa-chalkboard-teacher me-1"></i>

                Danh sách giáo viên dạy môn
                {{ $subject->subject_name }}

            </h5>


            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-primary">

                    <tr>

                        <th>#</th>
                        <th>Mã GV</th>
                        <th>Họ tên</th>
                        <th>Lớp</th>
                        <th>Năm học</th>
                        <th>Thứ</th>
                        <th>Tiết</th>
                        <th>Thời gian</th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $subject->assignments
                        as $i => $assignment
                    )

                        <tr>

                            <td>
                                {{ $i + 1 }}
                            </td>

                            <td>
                                <strong>
                                    {{ $assignment->teacher->teacher_code }}
                                </strong>
                            </td>

                            <td>
                                {{ $assignment->teacher->full_name }}
                            </td>

                            <td>
                                {{ $assignment->schoolClass->class_name }}
                            </td>

                            <td>
                                {{ $assignment->schoolYear->name }}
                            </td>

                            <td>
                                {{ $assignment->day_name }}
                            </td>

                            <td>
                                Tiết {{ $assignment->period }}
                            </td>

                            <td>

                                {{ optional($assignment->start_date)->format('d/m/Y') }}

                                -

                                {{ optional($assignment->end_date)->format('d/m/Y') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center text-muted py-4"
                            >
                                Chưa có giáo viên dạy môn này.
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