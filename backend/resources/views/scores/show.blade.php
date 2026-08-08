@extends('layouts.app')

@section('title', 'Chi tiết điểm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Chi tiết điểm
        </h2>

        <p class="text-muted mb-0">
            Thông tin kết quả học tập của học sinh
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('scores.edit', $score) }}"
           class="btn btn-warning">

            <i class="fa-solid fa-pen me-1"></i>

            Sửa

        </a>

        <a href="{{ route('scores.index') }}"
           class="btn btn-secondary">

            <i class="fa-solid fa-arrow-left me-1"></i>

            Quay lại

        </a>

    </div>

</div>


<div class="row">

    {{-- THÔNG TIN HỌC SINH --}}

    <div class="col-lg-5 mb-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="fa-solid fa-user-graduate me-2"></i>

                    Thông tin học sinh

                </h5>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <small class="text-muted">
                        Mã học sinh
                    </small>

                    <div class="fw-semibold">

                        {{ $score->student->student_code ?? 'N/A' }}

                    </div>

                </div>


                <div class="mb-3">

                    <small class="text-muted">
                        Họ và tên
                    </small>

                    <div class="fw-semibold fs-5">

                        {{ $score->student->full_name ?? 'N/A' }}

                    </div>

                </div>


                <div class="mb-3">

                    <small class="text-muted">
                        Môn học
                    </small>

                    <div class="fw-semibold">

                        {{ $score->subject->subject_name ?? 'N/A' }}

                    </div>

                </div>


                <div>

                    <small class="text-muted">
                        Năm học
                    </small>

                    <div class="fw-semibold">

                        {{ $score->schoolYear->name ?? 'N/A' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- BẢNG ĐIỂM --}}

    <div class="col-lg-7 mb-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0">

                    <i class="fa-solid fa-chart-column me-2"></i>

                    Kết quả học tập

                </h5>

            </div>

            <div class="card-body">

                <table class="table align-middle">

                    <tbody>

                        <tr>

                            <td>
                                Điểm miệng
                            </td>

                            <td class="text-end fw-semibold">

                                {{ $score->oral_score ?? '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Điểm 15 phút
                            </td>

                            <td class="text-end fw-semibold">

                                {{ $score->fifteen_minute_score ?? '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Điểm giữa kỳ
                            </td>

                            <td class="text-end fw-semibold">

                                {{ $score->midterm_score ?? '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Điểm cuối kỳ
                            </td>

                            <td class="text-end fw-semibold">

                                {{ $score->final_score ?? '-' }}

                            </td>

                        </tr>


                        <tr class="table-primary">

                            <td class="fw-bold">

                                Điểm trung bình

                            </td>

                            <td class="text-end">

                                <span class="badge bg-primary fs-6">

                                    {{ $score->average_score !== null
                                        ? number_format($score->average_score, 2)
                                        : '-' }}

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td class="fw-bold">

                                Xếp loại

                            </td>

                            <td class="text-end">

                                @if($score->classification)

                                    <span class="badge bg-success">

                                        {{ $score->classification }}

                                    </span>

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection