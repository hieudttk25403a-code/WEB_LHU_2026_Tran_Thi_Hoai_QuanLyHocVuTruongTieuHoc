@extends('layouts.app')

@section('title', 'Quản lý giáo viên')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Quản lý giáo viên</h2>

    <a href="{{ route('teachers.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i>
        Thêm giáo viên
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

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <form method="GET" action="{{ route('teachers.index') }}">

            <div class="row">

                <div class="col-md-10">

                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Nhập mã giáo viên hoặc họ tên..."
                        value="{{ request('keyword') }}">

                </div>

                <div class="col-md-2 d-grid">

                    <button class="btn btn-primary">

                        <i class="fa-solid fa-search"></i>

                        Tìm

                    </button>

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
                    <th>Mã GV</th>
                    <th>Họ và tên</th>
                    <th>Chuyên môn</th>
                    <th>Tổ</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Trạng thái</th>
                    <th width="220">Thao tác</th>

                </tr>

            </thead>

            <tbody>

                @forelse($teachers as $teacher)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $teacher->teacher_code }}</td>

                    <td>{{ $teacher->full_name }}</td>

                    <td>{{ $teacher->specialization }}</td>

                    <td>{{ $teacher->department }}</td>

                    <td>{{ $teacher->phone }}</td>

                    <td>{{ $teacher->email }}</td>

                    <td>
                        <span class="badge bg-success">
                            {{ $teacher->status }}
                        </span>
                    </td>

                    <td>

                        <a href="{{ route('teachers.show', $teacher) }}"
                           class="btn btn-info btn-sm">

                            <i class="fa-solid fa-eye"></i>

                        </a>

                        <a href="{{ route('teachers.edit', $teacher) }}"
                           class="btn btn-warning btn-sm">

                            <i class="fa-solid fa-pen"></i>

                        </a>

                        <form action="{{ route('teachers.destroy', $teacher) }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa giáo viên này?')">

                                <i class="fa-solid fa-trash"></i>

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="9" class="text-center">

                        Chưa có dữ liệu.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        <div class="mt-3">

            {{ $teachers->links() }}

        </div>

    </div>

</div>

@endsection