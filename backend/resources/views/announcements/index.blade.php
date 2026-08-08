@extends('layouts.app')

@section('title', 'Quản lý thông báo')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Quản lý thông báo
        </h2>

        <p class="text-muted mb-0">
            Quản lý các thông báo của nhà trường
        </p>
    </div>

    <a href="{{ route('announcements.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus me-1"></i>

        Thêm thông báo

    </a>

</div>


{{-- THÔNG BÁO THÀNH CÔNG --}}

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


{{-- DANH SÁCH --}}

<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-primary">

                    <tr>

                        <th>#</th>

                        <th>Tiêu đề</th>

                        <th>Người tạo</th>

                        <th>Ngày tạo</th>

                        <th>Trạng thái</th>

                        <th class="text-center">
                            Thao tác
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($announcements as $announcement)

                    <tr>

                        <td>
                            {{ $announcements->firstItem() + $loop->index }}
                        </td>


                        <td>

                            <div class="fw-semibold">

                                {{ $announcement->title }}

                            </div>

                            <small class="text-muted">

                                {{ \Illuminate\Support\Str::limit($announcement->content, 80) }}

                            </small>

                        </td>


                        <td>

                            {{ $announcement->creator->name ?? 'N/A' }}

                        </td>


                        <td>

                            {{ $announcement->created_at->format('d/m/Y H:i') }}

                        </td>


                        <td>

                            @if($announcement->is_published)

                                <span class="badge bg-success">

                                    <i class="fa-solid fa-eye me-1"></i>

                                    Đang hiển thị

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    <i class="fa-solid fa-eye-slash me-1"></i>

                                    Đã ẩn

                                </span>

                            @endif

                        </td>


                        <td>

                            <div class="d-flex justify-content-center gap-1">

                                {{-- Xem --}}

                                <a href="{{ route('announcements.show', $announcement) }}"
                                   class="btn btn-info btn-sm"
                                   title="Xem">

                                    <i class="fa-solid fa-eye"></i>

                                </a>


                                {{-- Sửa --}}

                                <a href="{{ route('announcements.edit', $announcement) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Sửa">

                                    <i class="fa-solid fa-pen"></i>

                                </a>


                                {{-- Xóa --}}

                                <form
                                    action="{{ route('announcements.destroy', $announcement) }}"
                                    method="POST"
                                    style="display:inline;">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Xóa"
                                        onclick="return confirm('Bạn có chắc muốn xóa thông báo này không?')">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center py-5">

                            <i class="fa-solid fa-bell fa-2x text-muted mb-3"></i>

                            <p class="text-muted mb-0">

                                Chưa có thông báo nào.

                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- PHÂN TRANG --}}

        <div class="mt-3">

            {{ $announcements->links() }}

        </div>

    </div>

</div>

@endsection