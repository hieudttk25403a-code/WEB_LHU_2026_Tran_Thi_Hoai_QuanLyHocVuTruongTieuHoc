@extends('layouts.app')

@section('title', 'Quản lý lớp học')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Quản lý lớp học
        </h2>

        <p class="text-muted mb-0">
            Danh sách các lớp học trong trường
        </p>
    </div>

    <a href="{{ route('classes.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus me-1"></i>

        Thêm lớp

    </a>

</div>


{{-- Thông báo --}}

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


{{-- Tìm kiếm --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <form method="GET"
              action="{{ route('classes.index') }}">

            <div class="row g-2">

                <div class="col-md-10">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="fa-solid fa-magnifying-glass"></i>

                        </span>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Tìm theo tên lớp hoặc khối..."
                            value="{{ request('keyword') }}">

                    </div>

                </div>

                <div class="col-md-2 d-grid">

                    <button class="btn btn-primary">

                        <i class="fa-solid fa-search me-1"></i>

                        Tìm kiếm

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- Danh sách --}}

<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-primary">

                    <tr>

                        <th>#</th>

                        <th>Tên lớp</th>

                        <th>Khối</th>

                        <th>GVCN</th>

                        <th>Sĩ số</th>

                        <th>Trạng thái</th>

                        <th class="text-center">
                            Thao tác
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($classes as $class)

                    <tr>

                        <td>
                            {{ $classes->firstItem() + $loop->index }}
                        </td>

                        <td>

                            <strong>
                                {{ $class->class_name }}
                            </strong>

                        </td>

                        <td>

                            <span class="badge bg-info">
                                Khối {{ $class->grade }}
                            </span>

                        </td>

                        <td>
                            {{ $class->homeroom_teacher ?? 'Chưa phân công' }}
                        </td>

                        <td>

                            <span class="badge bg-secondary">

                                {{ $class->student_count }}

                                học sinh

                            </span>

                        </td>

                        <td>

                            @if($class->status == 'Đang hoạt động')

                                <span class="badge bg-success">
                                    Đang hoạt động
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $class->status }}
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="d-flex justify-content-center gap-1">

                                {{-- Xem --}}

                                <a href="{{ route('classes.show', $class) }}"
                                   class="btn btn-info btn-sm"
                                   title="Xem">

                                    <i class="fa-solid fa-eye"></i>

                                </a>


                                {{-- Sửa --}}

                                <a href="{{ route('classes.edit', $class) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Sửa">

                                    <i class="fa-solid fa-pen"></i>

                                </a>


                                {{-- Xóa --}}

                                <form
                                    action="{{ route('classes.destroy', $class) }}"
                                    method="POST"
                                    style="display:inline;">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Xóa"
                                        onclick="return confirm('Bạn có chắc muốn xóa lớp này không?')">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="text-center py-5">

                            <i class="fa-solid fa-school fa-2x text-muted mb-3"></i>

                            <p class="text-muted mb-0">

                                Chưa có dữ liệu lớp học.

                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}

        <div class="mt-3">

            {{ $classes->links() }}

        </div>

    </div>

</div>

@endsection