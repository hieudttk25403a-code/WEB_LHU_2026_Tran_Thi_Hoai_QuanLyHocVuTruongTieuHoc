@extends('layouts.app')

@section('title', 'Quản lý học sinh')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý học sinh</h2>

    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i>
        Thêm học sinh
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close">
    </button>
</div>
@endif

<!-- Form tìm kiếm -->
<div class="card shadow-sm mb-4">
    <div class="card-body">

        <form method="GET" action="{{ route('students.index') }}">

            <div class="row">

                <div class="col-md-10">

                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Nhập mã học sinh, họ tên hoặc email..."
                        value="{{ request('keyword') }}">

                </div>

                <div class="col-md-2">

                    <div class="d-flex gap-2">

                        <button class="btn btn-primary flex-fill">
                            <i class="fa-solid fa-search"></i>
                        </button>

                        <a href="{{ route('students.index') }}"
                           class="btn btn-secondary">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>

<div class="card shadow-sm">

    <div class="card-body">

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-primary">

                <tr>
                    <th width="60">STT</th>
                    <th>Mã HS</th>
                    <th>Họ và tên</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Trạng thái</th>
                    <th width="220">Thao tác</th>
                </tr>

            </thead>

            <tbody>

                @forelse($students as $student)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $student->student_code }}</td>

                    <td>{{ $student->full_name }}</td>

                    <td>{{ $student->date_of_birth }}</td>

                    <td>{{ $student->gender }}</td>

                    <td>{{ $student->email }}</td>

                    <td>{{ $student->phone }}</td>

                    <td>
                        <span class="badge bg-success">
                            {{ $student->status }}
                        </span>
                    </td>

                    <td>

                        <a href="{{ route('students.show', $student->id) }}"
                           class="btn btn-info btn-sm">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="{{ route('students.edit', $student->id) }}"
                           class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form action="{{ route('students.destroy', $student->id) }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa?')">

                                <i class="fa-solid fa-trash"></i>

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="9" class="text-center">
                        Chưa có dữ liệu học sinh.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        <div class="mt-3">
            {{ $students->links() }}
        </div>

    </div>

</div>

@endsection