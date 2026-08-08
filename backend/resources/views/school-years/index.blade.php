@extends('layouts.app')

@section('title', 'Quản lý năm học')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">
            Quản lý năm học
        </h2>

        <p class="text-muted mb-0">
            Quản lý các năm học của trường
        </p>

    </div>

    <a href="{{ route('school-years.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus me-1"></i>

        Thêm năm học

    </a>

</div>


@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="fa-solid fa-circle-check me-1"></i>

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Tên năm học</th>

                        <th>Ngày bắt đầu</th>

                        <th>Ngày kết thúc</th>

                        <th>Trạng thái</th>

                        <th class="text-center">
                            Thao tác
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($schoolYears as $schoolYear)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                <strong>
                                    {{ $schoolYear->name }}
                                </strong>

                            </td>


                            <td>

                                {{ $schoolYear->start_date
                                    ? $schoolYear->start_date->format('d/m/Y')
                                    : '—' }}

                            </td>


                            <td>

                                {{ $schoolYear->end_date
                                    ? $schoolYear->end_date->format('d/m/Y')
                                    : '—' }}

                            </td>


                            <td>

                                @if($schoolYear->is_active)

                                    <span class="badge bg-success">

                                        <i class="fa-solid fa-circle-check me-1"></i>

                                        Đang hoạt động

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        <i class="fa-solid fa-circle-xmark me-1"></i>

                                        Đã kết thúc

                                    </span>

                                @endif

                            </td>


                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-1">

                                    {{-- Xem --}}

                                    <a href="{{ route('school-years.show', $schoolYear) }}"
                                       class="btn btn-info btn-sm"
                                       title="Xem">

                                        <i class="fa-solid fa-eye"></i>

                                    </a>


                                    {{-- Sửa --}}

                                    <a href="{{ route('school-years.edit', $schoolYear) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Sửa">

                                        <i class="fa-solid fa-pen"></i>

                                    </a>


                                    {{-- Xóa --}}

                                    <form action="{{ route('school-years.destroy', $schoolYear) }}"
                                          method="POST"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa năm học này?')">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Xóa">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-muted py-4">

                                Chưa có dữ liệu năm học.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="mt-3">

            {{ $schoolYears->links() }}

        </div>

    </div>

</div>

@endsection