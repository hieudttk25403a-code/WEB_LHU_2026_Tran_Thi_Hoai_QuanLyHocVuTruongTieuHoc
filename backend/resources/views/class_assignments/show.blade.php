@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Chi tiết phân công chủ nhiệm
            </h2>

            <p class="text-muted mb-0">
                Thông tin giáo viên chủ nhiệm và lớp phụ trách
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


    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <div class="row g-4">

                {{-- GIÁO VIÊN --}}
                <div class="col-md-6">

                    <div class="text-muted mb-1">
                        Mã giáo viên
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $classAssignment->teacher->teacher_code ?? 'Không có' }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted mb-1">
                        Họ tên giáo viên
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $classAssignment->teacher->full_name ?? 'Không có' }}

                    </div>

                </div>


                {{-- LỚP --}}
                <div class="col-md-6">

                    <div class="text-muted mb-1">
                        Lớp chủ nhiệm
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $classAssignment->schoolClass->class_name ?? 'Không có' }}

                    </div>

                </div>


                {{-- NĂM HỌC --}}
                <div class="col-md-6">

                    <div class="text-muted mb-1">
                        Năm học
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $classAssignment->schoolYear->name
                            ?? $classAssignment->schoolYear->school_year
                            ?? (
                                ($classAssignment->schoolYear->start_year ?? '')
                                . ' - ' .
                                ($classAssignment->schoolYear->end_year ?? '')
                            )
                        }}

                    </div>

                </div>

            </div>


            <hr class="my-4">


            <div class="d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'class-assignments.edit',
                        $classAssignment
                    ) }}"
                    class="btn btn-warning"
                >

                    <i class="fas fa-edit me-1"></i>

                    Chỉnh sửa

                </a>

            </div>

        </div>

    </div>

</div>

@endsection