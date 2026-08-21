```blade
@extends('layouts.app')

@section('title', 'Chi tiết giáo viên')

@section('content')

<div class="card shadow-sm">

    {{-- HEADER --}}
    <div class="card-header bg-info text-white">
        <h4 class="mb-0">
            <i class="fas fa-user-tie me-2"></i>
            Chi tiết giáo viên
        </h4>
    </div>

    {{-- THÔNG TIN GIÁO VIÊN --}}
    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Mã giáo viên</label>
                <p class="mb-0">
                    {{ $teacher->teacher_code }}
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Họ và tên</label>
                <p class="mb-0">
                    {{ $teacher->full_name }}
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Chuyên môn</label>
                <p class="mb-0">
                    {{ $teacher->specialization ?: '—' }}
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Tổ</label>
                <p class="mb-0">
                    {{ $teacher->department ?: '—' }}
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Số điện thoại</label>
                <p class="mb-0">
                    {{ $teacher->phone ?: '—' }}
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Email</label>
                <p class="mb-0">
                    {{ $teacher->email ?: '—' }}
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Trạng thái</label>

                @if($teacher->status == 'Đang công tác')

                    <span class="badge bg-success">
                        {{ $teacher->status }}
                    </span>

                @else

                    <span class="badge bg-danger">
                        {{ $teacher->status }}
                    </span>

                @endif

            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Ngày tạo</label>
                <p class="mb-0">
                    {{ $teacher->created_at?->format('d/m/Y H:i') ?? '—' }}
                </p>
            </div>

        </div>

        {{-- NÚT --}}
        <div class="mt-3">

            <a href="{{ route('teachers.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Quay lại

            </a>

            <a href="{{ route('teachers.edit', $teacher) }}"
               class="btn btn-warning">

                <i class="fas fa-pen me-1"></i>
                Chỉnh sửa

            </a>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- PHÂN CÔNG GIẢNG DẠY --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mt-4">

    <div class="card-header bg-white">

        <h5 class="fw-bold mb-0">

            <i class="fas fa-chalkboard-teacher me-2"></i>

            Phân công giảng dạy

        </h5>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Môn học</th>

                        <th>Lớp</th>

                        <th>Năm học</th>

                        <th>Thời gian</th>

                        <th>Ghi chú</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($teacher->subjectAssignments as $assignment)

                        <tr>

                            {{-- STT --}}
                            <td>
                                {{ $loop->iteration }}
                            </td>


                            {{-- MÔN HỌC --}}
                            <td>

                                <span class="fw-semibold">

                                    {{ $assignment->subject->subject_name ?? '—' }}

                                </span>

                            </td>


                            {{-- LỚP --}}
                            <td>

                                <span class="badge bg-info text-dark">

                                    {{ $assignment->schoolClass->class_name ?? '—' }}

                                </span>

                            </td>


                            {{-- NĂM HỌC --}}
                            <td>

                                {{ $assignment->schoolYear->name ?? '—' }}

                            </td>


                            {{-- THỜI GIAN --}}
                            <td>

                                @if($assignment->start_date)

                                    {{ $assignment->start_date->format('d/m/Y') }}

                                @else

                                    —

                                @endif


                                →


                                @if($assignment->end_date)

                                    {{ $assignment->end_date->format('d/m/Y') }}

                                @else

                                    Đang dạy

                                @endif

                            </td>


                            {{-- GHI CHÚ --}}
                            <td>

                                {{ $assignment->note ?: '—' }}

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-muted py-4">

                                <i class="fas fa-info-circle me-1"></i>

                                Giáo viên này chưa có phân công bộ môn.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
```
