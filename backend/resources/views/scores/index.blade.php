@extends('layouts.app')

@section('title', 'Quản lý điểm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Quản lý điểm
        </h2>

        <p class="text-muted mb-0">
            Quản lý kết quả học tập của học sinh
        </p>
    </div>

    <a href="{{ route('scores.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus me-1"></i>

        Nhập điểm

    </a>

</div>


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

    <div class="card-body">

        <form method="GET"
              action="{{ route('scores.index') }}">

            <div class="row g-3">

                {{-- Tìm kiếm --}}

                <div class="col-md-4">

                    <label class="form-label fw-semibold">

                        Tìm học sinh

                    </label>

                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Mã HS hoặc họ tên..."
                        value="{{ request('keyword') }}">

                </div>


                {{-- Môn học --}}

                <div class="col-md-3">

                    <label class="form-label fw-semibold">

                        Môn học

                    </label>

                    <select name="subject_id"
                            class="form-select">

                        <option value="">

                            -- Tất cả môn --

                        </option>

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                {{ request('subject_id') == $subject->id ? 'selected' : '' }}>

                                {{ $subject->subject_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Năm học --}}

                <div class="col-md-3">

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


                {{-- Nút --}}

                <div class="col-md-2 d-flex align-items-end">

                    <button type="submit"
                            class="btn btn-primary w-100">

                        <i class="fa-solid fa-filter me-1"></i>

                        Lọc

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- DANH SÁCH --}}

<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-primary">

                    <tr>

                        <th>#</th>

                        <th>Học sinh</th>

                        <th>Môn học</th>

                        <th>Năm học</th>

                        <th>Điểm miệng</th>

                        <th>Điểm 15 phút</th>

                        <th>Giữa kỳ</th>

                        <th>Cuối kỳ</th>

                        <th>Điểm TB</th>

                        <th>Xếp loại</th>

                        <th class="text-center">

                            Thao tác

                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($scores as $score)

                    <tr>

                        <td>

                            {{ $scores->firstItem() + $loop->index }}

                        </td>


                        <td>

                            <strong>

                                {{ $score->student->full_name ?? 'N/A' }}

                            </strong>

                            <br>

                            <small class="text-muted">

                                {{ $score->student->student_code ?? '' }}

                            </small>

                        </td>


                        <td>

                            {{ $score->subject->subject_name ?? 'N/A' }}

                        </td>


                        <td>

                            {{ $score->schoolYear->name ?? 'N/A' }}

                        </td>


                        <td>

                            {{ $score->oral_score ?? '-' }}

                        </td>


                        <td>

                            {{ $score->fifteen_minute_score ?? '-' }}

                        </td>


                        <td>

                            {{ $score->midterm_score ?? '-' }}

                        </td>


                        <td>

                            {{ $score->final_score ?? '-' }}

                        </td>


                        <td>

                            @if($score->average_score !== null)

                                <span class="badge bg-primary">

                                    {{ number_format($score->average_score, 2) }}

                                </span>

                            @else

                                -

                            @endif

                        </td>


                        <td>

                            @if($score->classification)

                                <span class="badge bg-success">

                                    {{ $score->classification }}

                                </span>

                            @else

                                -

                            @endif

                        </td>


                        <td>

                            <div class="d-flex justify-content-center gap-1">

                                <a href="{{ route('scores.show', $score) }}"
                                   class="btn btn-info btn-sm"
                                   title="Xem">

                                    <i class="fa-solid fa-eye"></i>

                                </a>


                                <a href="{{ route('scores.edit', $score) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Sửa">

                                    <i class="fa-solid fa-pen"></i>

                                </a>


                                <form
                                    action="{{ route('scores.destroy', $score) }}"
                                    method="POST"
                                    style="display:inline;">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Xóa"
                                        onclick="return confirm('Bạn có chắc muốn xóa bảng điểm này không?')">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="11"
                            class="text-center py-5">

                            <i class="fa-solid fa-chart-line fa-2x text-muted mb-3"></i>

                            <p class="text-muted mb-0">

                                Chưa có dữ liệu điểm.

                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        <div class="mt-3">

            {{ $scores->links() }}

        </div>

    </div>

</div>

@endsection