@extends('layouts.app')

@section('title', 'Thời khóa biểu')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Quản lý thời khóa biểu
        </h2>

        <p class="text-muted mb-0">
            Quản lý lịch học theo lớp và giáo viên
        </p>
    </div>

    <a href="{{ route('timetables.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus me-1"></i>

        Thêm thời khóa biểu

    </a>

</div>


{{-- THÔNG BÁO THÀNH CÔNG --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="fa-solid fa-circle-check me-2"></i>

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- BỘ LỌC --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-light">

        <h5 class="mb-0">

            <i class="fa-solid fa-filter me-2"></i>

            Bộ lọc

        </h5>

    </div>


    <div class="card-body">

        <form method="GET"
              action="{{ route('timetables.index') }}">

            <div class="row g-3">


                {{-- LỚP --}}

                <div class="col-md-4">

                    <label class="form-label fw-semibold">

                        Lớp

                    </label>

                    <select name="class_id"
                            class="form-select">

                        <option value="">
                            -- Tất cả lớp --
                        </option>

                        @foreach($classes as $class)

                            <option
                                value="{{ $class->id }}"
                                {{ request('class_id') == $class->id ? 'selected' : '' }}>

                                {{ $class->class_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- GIÁO VIÊN --}}

                <div class="col-md-4">

                    <label class="form-label fw-semibold">

                        Giáo viên

                    </label>

                    <select name="teacher_id"
                            class="form-select">

                        <option value="">
                            -- Tất cả giáo viên --
                        </option>

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>

                                {{ $teacher->full_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- NĂM HỌC --}}

                <div class="col-md-4">

                    <label class="form-label fw-semibold">

                        Năm học

                    </label>

                    <select name="school_year_id"
                            class="form-select">

                        <option value="">
                            -- Tất cả năm học --
                        </option>

                        @foreach($schoolYears as $schoolYear)

                            <option
                                value="{{ $schoolYear->id }}"
                                {{ request('school_year_id') == $schoolYear->id ? 'selected' : '' }}>

                                {{ $schoolYear->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- NÚT LỌC --}}

                <div class="col-12">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fa-solid fa-filter me-1"></i>

                        Lọc

                    </button>

                    <a href="{{ route('timetables.index') }}"
                       class="btn btn-secondary">

                        <i class="fa-solid fa-rotate-left me-1"></i>

                        Xóa lọc

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- DANH SÁCH THỜI KHÓA BIỂU --}}

<div class="card shadow-sm border-0">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-calendar-days me-2"></i>

            Danh sách thời khóa biểu

        </h5>

    </div>


    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-primary">

                    <tr>

                        <th>#</th>

                        <th>Lớp</th>

                        <th>Môn học</th>

                        <th>Giáo viên</th>

                        <th>Thứ</th>

                        <th>Thời gian</th>

                        <th>Phòng</th>

                        <th class="text-center">
                            Thao tác
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($timetables as $timetable)

                    <tr>


                        {{-- STT --}}

                        <td>

                            {{ $timetables->firstItem() + $loop->index }}

                        </td>


                        {{-- LỚP --}}

                        <td class="fw-semibold">

                            {{ $timetable->schoolClass->class_name ?? 'N/A' }}

                        </td>


                        {{-- MÔN HỌC --}}

                        <td>

                            {{ $timetable->subject->subject_name ?? 'N/A' }}

                        </td>


                        {{-- GIÁO VIÊN --}}

                        <td>

                            {{ $timetable->teacher->full_name ?? 'N/A' }}

                        </td>


                        {{-- THỨ --}}

                        <td>

                            <span class="badge bg-primary">

                                {{ $timetable->day_of_week }}

                            </span>

                        </td>


                        {{-- THỜI GIAN --}}

                        <td>

                            <div class="fw-semibold">

                                {{ \Carbon\Carbon::parse($timetable->start_time)->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse($timetable->end_time)->format('H:i') }}

                            </div>

                        </td>


                        {{-- PHÒNG --}}

                        <td>

                            {{ $timetable->room ?? '-' }}

                        </td>


                        {{-- THAO TÁC --}}

                        <td>

                            <div class="d-flex justify-content-center gap-1">


                                {{-- XEM --}}

                                <a href="{{ route('timetables.show', $timetable) }}"
                                   class="btn btn-info btn-sm"
                                   title="Xem">

                                    <i class="fa-solid fa-eye"></i>

                                </a>


                                {{-- SỬA --}}

                                <a href="{{ route('timetables.edit', $timetable) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Sửa">

                                    <i class="fa-solid fa-pen"></i>

                                </a>


                                {{-- XÓA --}}

                                <form
                                    action="{{ route('timetables.destroy', $timetable) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Xóa"
                                        onclick="return confirm('Bạn có chắc muốn xóa lịch học này không?')">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td colspan="8"
                            class="text-center py-5">

                            <i class="fa-solid fa-calendar-days fa-2x text-muted mb-3"></i>

                            <p class="text-muted mb-0">

                                Chưa có thời khóa biểu.

                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- PHÂN TRANG --}}

        <div class="mt-3">

            {{ $timetables->links() }}

        </div>

    </div>

</div>

@endsection