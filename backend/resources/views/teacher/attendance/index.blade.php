@extends('layouts.app')

@section('title', 'Điểm danh')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Điểm danh học sinh
            </h2>

            <p class="text-muted mb-0">
                Danh sách các lớp được phân công
            </p>
        </div>

    </div>


    {{-- THÔNG BÁO --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- LỌC NĂM HỌC --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <form method="GET"
                  action="{{ route('teacher.attendance.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">

                        <label class="form-label fw-bold">
                            Năm học
                        </label>

                        <select name="school_year_id"
                                class="form-select">

                            @foreach($schoolYears as $year)

                                <option
                                    value="{{ $year->id }}"
                                    @selected(
                                        optional($schoolYear)->id
                                        == $year->id
                                    )
                                >
                                    {{ $year->name }}

                                    @if($year->is_active)
                                        (Đang học)
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <button class="btn btn-success w-100">

                            <i class="fas fa-search me-1"></i>

                            Xem lớp

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- DANH SÁCH LỚP --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4">

            <h5 class="fw-bold mb-1">
                Lớp được phân công
            </h5>

            <p class="text-muted mb-0">

                Giáo viên:
                <strong>
                    {{ $teacher->full_name }}
                </strong>

            </p>

        </div>


        <div class="card-body p-4">

            @if($classes->count())

                <div class="row g-4">

                    @foreach($classes as $class)

                        <div class="col-md-6 col-xl-4">

                            <div class="card h-100 border
                                        rounded-4 shadow-sm">

                                <div class="card-body p-4">

                                    <div class="d-flex
                                                justify-content-between
                                                align-items-start
                                                mb-3">

                                        <div>

                                            <span class="badge
                                                         bg-success
                                                         mb-2">

                                                Khối {{ $class->grade }}

                                            </span>

                                            <h4 class="fw-bold mb-0">

                                                {{ $class->class_name }}

                                            </h4>

                                        </div>

                                        <div
                                            class="rounded-4
                                                   bg-success
                                                   bg-opacity-10
                                                   p-3">

                                            <i class="fas fa-users
                                                      text-success
                                                      fs-4">
                                            </i>

                                        </div>

                                    </div>


                                    <div class="text-muted mb-4">

                                        <i class="fas fa-user-graduate me-1"></i>

                                        Sĩ số:

                                        {{ $class->students()->count() }}

                                        học sinh

                                    </div>


                                    <a
                                        href="{{ route(
                                            'teacher.attendance.create',
                                            [
                                                'class' =>
                                                    $class->id,

                                                'school_year_id' =>
                                                    optional($schoolYear)->id
                                            ]
                                        ) }}"
                                        class="btn btn-success w-100">

                                        <i class="fas fa-user-check me-1"></i>

                                        Điểm danh

                                    </a>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center py-5">

                    <i class="fas fa-users-slash
                              text-muted fs-1 mb-3">
                    </i>

                    <h5 class="fw-bold">
                        Chưa có lớp được phân công
                    </h5>

                    <p class="text-muted mb-0">

                        Bạn chưa được phân công lớp trong
                        năm học này.

                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection