@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                Quản lý môn học
            </h2>

            <p class="text-muted">
                Danh mục môn học và giáo viên đang giảng dạy.
            </p>

        </div>

        <a
            href="{{ route('subjects.create') }}"
            class="btn btn-primary"
        >
            <i class="fas fa-plus me-1"></i>
            Thêm môn học
        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-md-10">

                        <input
                            name="keyword"
                            value="{{ request('keyword') }}"
                            class="form-control"
                            placeholder="Tìm mã môn, tên môn..."
                        >

                    </div>

                    <div class="col-md-2">

                        <button
                            class="btn btn-primary w-100"
                        >
                            Tìm kiếm
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-primary">

                    <tr>

                        <th>#</th>
                        <th>Mã môn</th>
                        <th>Tên môn</th>
                        <th>Khối</th>
                        <th>Số GV</th>
                        <th>Thao tác</th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse($subjects as $i => $subject)

                        <tr>

                            <td>
                                {{ $subjects->firstItem() + $i }}
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
                                {{ $subject->grade }}
                            </td>

                            <td>

                                <span class="badge bg-success">
                                    {{ $subject->teachers_count }}
                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route('subjects.show', $subject) }}"
                                    class="btn btn-info btn-sm text-white"
                                >
                                    <i class="fas fa-eye"></i>
                                    Xem giáo viên
                                </a>

                                <a
                                    href="{{ route('subjects.edit', $subject) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    <i class="fas fa-pen"></i>
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                Chưa có môn học.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            {{ $subjects->links() }}

        </div>

    </div>

</div>

@endsection