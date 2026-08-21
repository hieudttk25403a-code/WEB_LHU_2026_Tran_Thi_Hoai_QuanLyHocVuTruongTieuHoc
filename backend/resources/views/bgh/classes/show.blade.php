@extends('layouts.app')

@section('title', 'Chi tiết lớp học')

@section('content')

<div class="container-fluid">


    {{-- =====================================================
         QUAY LẠI
    ====================================================== --}}

    <div class="mb-3">

        <a
            href="{{ route('bgh.classes.index') }}"
            class="btn btn-outline-secondary"
        >

            <i class="fa-solid fa-arrow-left me-1"></i>

            Quay lại danh sách lớp

        </a>

    </div>


    {{-- =====================================================
         THÔNG TIN LỚP
    ====================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="fa-solid fa-school me-2"></i>

                Thông tin lớp học

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- TÊN LỚP --}}

                <div class="col-md-3">

                    <div class="text-muted small">

                        Tên lớp

                    </div>

                    <div class="fw-bold fs-5">

                        {{ $schoolClass->class_name }}

                    </div>

                </div>


                {{-- KHỐI --}}

                <div class="col-md-3">

                    <div class="text-muted small">

                        Khối

                    </div>

                    <div class="fw-semibold">

                        Khối {{ $schoolClass->grade }}

                    </div>

                </div>


                {{-- GVCN --}}

                <div class="col-md-3">

                    <div class="text-muted small">

                        Giáo viên chủ nhiệm

                    </div>

                    <div class="fw-semibold">

                        @if(!empty($schoolClass->homeroom_teacher))

                            {{ $schoolClass->homeroom_teacher }}

                        @elseif(
                            isset($schoolClass->homeroomTeacher)
                            && $schoolClass->homeroomTeacher
                        )

                            {{ $schoolClass->homeroomTeacher->full_name }}

                        @else

                            Chưa phân công

                        @endif

                    </div>

                </div>


                {{-- SĨ SỐ --}}

                <div class="col-md-3">

                    <div class="text-muted small">

                        Sĩ số

                    </div>

                    <div>

                        <span class="badge bg-success fs-6">

                            {{ $schoolClass->students()->count() }}
                            học sinh

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         DANH SÁCH HỌC SINH
    ====================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-success text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fa-solid fa-user-graduate me-2"></i>

                    Danh sách học sinh

                </h5>

                <span>

                    {{ $students->total() }}
                    học sinh

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="text-center">
                                STT
                            </th>

                            <th>
                                Mã học sinh
                            </th>

                            <th>
                                Họ và tên
                            </th>

                            <th>
                                Ngày sinh
                            </th>

                            <th>
                                Giới tính
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th class="text-center">
                                Xem
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($students as $index => $student)

                            <tr>


                                {{-- STT --}}

                                <td class="text-center">

                                    {{
                                        ($students->currentPage() - 1)
                                        * $students->perPage()
                                        + $index
                                        + 1
                                    }}

                                </td>


                                {{-- MÃ --}}

                                <td>

                                    <strong>

                                        {{ $student->student_code }}

                                    </strong>

                                </td>


                                {{-- HỌ TÊN --}}

                                <td>

                                    {{ $student->full_name }}

                                </td>


                                {{-- NGÀY SINH --}}

                                <td>

                                    {{
                                        $student->date_of_birth
                                            ? \Carbon\Carbon::parse(
                                                $student->date_of_birth
                                            )->format('d/m/Y')
                                            : '—'
                                    }}

                                </td>


                                {{-- GIỚI TÍNH --}}

                                <td>

                                    {{ $student->gender ?? '—' }}

                                </td>


                                {{-- TRẠNG THÁI --}}

                                <td>

                                    @if(
                                        $student->status === 'Đang học'
                                    )

                                        <span class="badge bg-success">

                                            Đang học

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ $student->status }}

                                        </span>

                                    @endif

                                </td>


                                {{-- XEM HỌC SINH --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route('bgh.students.show', $student) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        <i class="fa-solid fa-eye me-1"></i>

                                        Xem

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="fa-solid fa-user-graduate text-muted"
                                        style="font-size:42px;"
                                    ></i>

                                    <p class="text-muted mt-3 mb-0">

                                        Lớp này chưa có học sinh.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PHÂN TRANG --}}

        @if($students->hasPages())

            <div class="card-footer bg-white">

                {{ $students->links() }}

            </div>

        @endif

    </div>


    {{-- QUYỀN BGH --}}

    <div class="alert alert-info mt-4">

        <i class="fa-solid fa-circle-info me-2"></i>

        Ban Giám Hiệu đang ở chế độ
        <strong>chỉ xem</strong> thông tin lớp học
        và danh sách học sinh.

    </div>

</div>

@endsection