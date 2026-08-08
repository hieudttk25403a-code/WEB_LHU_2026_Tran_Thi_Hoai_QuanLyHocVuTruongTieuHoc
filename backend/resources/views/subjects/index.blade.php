@extends('layouts.app')

@section('title', 'Quản lý môn học')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Quản lý môn học
        </h2>

        <p class="text-muted mb-0">
            Danh sách các môn học trong trường
        </p>
    </div>

    <a href="{{ route('subjects.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus me-1"></i>

        Thêm môn học

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


<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <form method="GET"
              action="{{ route('subjects.index') }}">

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
                            placeholder="Tìm theo mã môn, tên môn, giáo viên hoặc khối..."
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


<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-primary">

                    <tr>

                        <th>#</th>

                        <th>Mã môn</th>

                        <th>Tên môn</th>

                        <th>Giáo viên</th>

                        <th>Khối</th>

                        <th>Trạng thái</th>

                        <th class="text-center">
                            Thao tác
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($subjects as $subject)

                    <tr>

                        <td>
                            {{ $subjects->firstItem() + $loop->index }}
                        </td>

                        <td>

                            <strong>
                                {{ $subject->subject_code }}
                            </strong>

                        </td>

                        <td>
                            {{ $subject->subject_name }}
                        </td>

                        <td>
                            {{ $subject->teacher ?? 'Chưa phân công' }}
                        </td>

                        <td>

                            <span class="badge bg-info">

                                Khối {{ $subject->grade }}

                            </span>

                        </td>

                        <td>

                            @if($subject->status == 'Đang giảng dạy')

                                <span class="badge bg-success">

                                    Đang giảng dạy

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    {{ $subject->status }}

                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="d-flex justify-content-center gap-1">

                                <a href="{{ route('subjects.show', $subject) }}"
                                   class="btn btn-info btn-sm"
                                   title="Xem">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                <a href="{{ route('subjects.edit', $subject) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Sửa">

                                    <i class="fa-solid fa-pen"></i>

                                </a>

                                <form
                                    action="{{ route('subjects.destroy', $subject) }}"
                                    method="POST"
                                    style="display:inline;">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Xóa"
                                        onclick="return confirm('Bạn có chắc muốn xóa môn học này không?')">

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

                            <i class="fa-solid fa-book fa-2x text-muted mb-3"></i>

                            <p class="text-muted mb-0">

                                Chưa có dữ liệu môn học.

                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        <div class="mt-3">

            {{ $subjects->links() }}

        </div>

    </div>

</div>

@endsection