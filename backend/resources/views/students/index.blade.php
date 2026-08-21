@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Tiêu đề --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-user-graduate me-2 text-success"></i>
                Quản lý học sinh
            </h3>

            <p class="text-muted mb-0">
                Danh sách và thông tin học sinh
            </p>
        </div>

        <a href="{{ route('students.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i>
            Thêm học sinh
        </a>
    </div>


    {{-- Thông báo --}}
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


    {{-- Bộ lọc --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('students.index') }}">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Tìm kiếm
                        </label>

                        <input type="text"
                               name="keyword"
                               class="form-control"
                               value="{{ request('keyword') }}"
                               placeholder="Mã HS, họ tên, email, SĐT...">
                    </div>


                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Lớp
                        </label>

                        <select name="class_id"
                                class="form-select">

                            <option value="">
                                -- Tất cả lớp --
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>

                                    {{ $class->class_name }}

                                </option>

                            @endforeach

                        </select>
                    </div>


                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Trạng thái
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                -- Tất cả --
                            </option>

                            <option value="Đang học"
                                {{ request('status') == 'Đang học' ? 'selected' : '' }}>
                                Đang học
                            </option>

                            <option value="Chuyển trường"
                                {{ request('status') == 'Chuyển trường' ? 'selected' : '' }}>
                                Chuyển trường
                            </option>

                            <option value="Bảo lưu"
                                {{ request('status') == 'Bảo lưu' ? 'selected' : '' }}>
                                Bảo lưu
                            </option>

                            <option value="Đuổi học"
                                {{ request('status') == 'Đuổi học' ? 'selected' : '' }}>
                                Đuổi học
                            </option>

                        </select>
                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-search me-1"></i>
                            Tìm kiếm

                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>


    {{-- Bảng học sinh --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-success text-white">

            <div class="d-flex justify-content-between">

                <span class="fw-bold">
                    <i class="fas fa-users me-2"></i>
                    Danh sách học sinh
                </span>

                <span>
                    {{ $students->total() }} học sinh
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-primary">

                        <tr>

                            <th class="text-center">
                                STT
                            </th>

                            <th>
                                Mã HS
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
                                Lớp hiện tại
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                SĐT
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th class="text-center">
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($students as $index => $student)

                            <tr>

                                <td class="text-center">
                                    {{ $students->firstItem() + $index }}
                                </td>


                                <td class="fw-semibold">
                                    {{ $student->student_code }}
                                </td>


                                <td>
                                    <div class="fw-semibold">
                                        {{ $student->full_name }}
                                    </div>
                                </td>


                                <td>
                                    {{ $student->date_of_birth
                                        ? $student->date_of_birth->format('d/m/Y')
                                        : '-' }}
                                </td>


                                <td>
                                    {{ $student->gender }}
                                </td>


                                {{-- LỚP HIỆN TẠI --}}
                                <td>

                                    @if($student->schoolClass)

                                        <span class="badge bg-primary fs-6">

                                            {{ $student->schoolClass->class_name }}

                                        </span>

                                        <div class="small text-muted mt-1">

                                            Khối {{ $student->schoolClass->grade }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            Chưa xếp lớp
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $student->email ?? '-' }}
                                </td>


                                <td>
                                    {{ $student->phone ?? '-' }}
                                </td>


                                <td>

                                    @if($student->status == 'Đang học')

                                        <span class="badge bg-success">
                                            Đang học
                                        </span>

                                    @elseif($student->status == 'Bảo lưu')

                                        <span class="badge bg-warning text-dark">
                                            Bảo lưu
                                        </span>

                                    @elseif($student->status == 'Chuyển trường')

                                        <span class="badge bg-secondary">
                                            Chuyển trường
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            {{ $student->status }}
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <div class="d-flex gap-2">

                                        <a href="{{ route('students.show', $student) }}"
                                           class="btn btn-info btn-sm text-white"
                                           title="Xem chi tiết">

                                            <i class="fas fa-eye"></i>

                                        </a>


                                        <a href="{{ route('students.edit', $student) }}"
                                           class="btn btn-warning btn-sm"
                                           title="Chỉnh sửa">

                                            <i class="fas fa-edit"></i>

                                        </a>


                                        <form action="{{ route('students.destroy', $student) }}"
                                              method="POST"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa học sinh này không?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    title="Xóa">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10"
                                    class="text-center py-5">

                                    <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>

                                    <div class="text-muted">
                                        Không tìm thấy học sinh.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($students->hasPages())

            <div class="card-footer">

                {{ $students->links() }}

            </div>

        @endif

    </div>

</div>

@endsection