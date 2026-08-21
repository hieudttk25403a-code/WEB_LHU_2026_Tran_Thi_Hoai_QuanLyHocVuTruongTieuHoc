@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold">
            Phân công giáo viên
        </h2>

        <p class="text-muted">
            Chọn loại phân công cần thực hiện.
        </p>
    </div>

    <div class="row g-4">

        {{-- CHỦ NHIỆM --}}
        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="fs-1 text-primary mb-3">
                        <i class="fas fa-user-tie"></i>
                    </div>

                    <h4 class="fw-bold">
                        Phân công giáo viên chủ nhiệm
                    </h4>

                    <p class="text-muted">
                        Chọn giáo viên, lớp và năm học.
                        Nếu là giáo viên chuyên,
                        hệ thống tự động xác định môn chuyên.
                    </p>

                    <a
                        href="{{ route('class-assignments.create') }}"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-user-check me-1"></i>
                        Phân công chủ nhiệm
                    </a>

                </div>

            </div>

        </div>


        {{-- BỘ MÔN --}}
        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="fs-1 text-success mb-3">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>

                    <h4 class="fw-bold">
                        Phân công giáo viên bộ môn
                    </h4>

                    <p class="text-muted">
                        Giáo viên thường có thể dạy nhiều môn.
                        Giáo viên chuyên Anh/Tin được hệ thống
                        tự động khóa đúng môn chuyên.
                    </p>

                    <a
                        href="{{ route('teacher-subject-assignments.create') }}"
                        class="btn btn-success"
                    >
                        <i class="fas fa-book-open me-1"></i>
                        Phân công bộ môn
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection