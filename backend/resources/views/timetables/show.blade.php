@extends('layouts.app')

@section('title', 'Chi tiết thời khóa biểu')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Chi tiết thời khóa biểu
        </h2>

        <p class="text-muted mb-0">
            Thông tin lịch học
        </p>
    </div>

    <a href="{{ route('timetables.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-info text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-calendar-days me-2"></i>

            Thông tin thời khóa biểu

        </h5>

    </div>


    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">

                <strong>Lớp:</strong>

                <div>
                    {{ $timetable->schoolClass->class_name ?? '---' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <strong>Môn học:</strong>

                <div>
                    {{ $timetable->subject->subject_name ?? '---' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <strong>Giáo viên:</strong>

                <div>
                    {{ $timetable->teacher->full_name ?? '---' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <strong>Năm học:</strong>

                <div>
                    {{ $timetable->schoolYear->name ?? '---' }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <strong>Thứ:</strong>

                <div>
                    {{ $timetable->day_of_week }}
                </div>

            </div>


            <div class="col-md-3 mb-3">

                <strong>Giờ bắt đầu:</strong>

                <div>
                    {{ \Carbon\Carbon::parse($timetable->start_time)->format('H:i') }}
                </div>

            </div>


            <div class="col-md-3 mb-3">

                <strong>Giờ kết thúc:</strong>

                <div>
                    {{ \Carbon\Carbon::parse($timetable->end_time)->format('H:i') }}
                </div>

            </div>


            <div class="col-md-6 mb-3">

                <strong>Phòng học:</strong>

                <div>
                    {{ $timetable->room ?? 'Không có' }}
                </div>

            </div>

        </div>


        <hr>


        <div class="d-flex gap-2">

            <a href="{{ route('timetables.edit', $timetable) }}"
               class="btn btn-warning">

                <i class="fa-solid fa-pen me-1"></i>

                Sửa

            </a>

            <a href="{{ route('timetables.index') }}"
               class="btn btn-secondary">

                Quay lại

            </a>

        </div>

    </div>

</div>

@endsection