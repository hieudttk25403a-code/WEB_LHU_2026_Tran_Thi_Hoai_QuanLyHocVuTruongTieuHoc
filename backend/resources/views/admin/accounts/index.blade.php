<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý tài khoản - Hệ thống quản lý học vụ</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>
        body {
            background-color: #f5f7fa;
        }

        .page-header {
            background: #198754;
            color: white;
            border-radius: 12px;
            padding: 22px 25px;
            margin-bottom: 25px;
        }

        .page-header h3 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .page-header p {
            margin-bottom: 0;
            opacity: 0.9;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .table thead th {
            background-color: #f0f2f5;
            white-space: nowrap;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
        }

        .account-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #198754;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .badge-role {
            padding: 7px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .empty-state {
            padding: 50px 20px;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 50px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="container-fluid py-4 px-4">

    {{-- Tiêu đề --}}
    <div class="page-header d-flex justify-content-between align-items-center">

        <div>
            <h3>
                <i class="fa-solid fa-users-gear me-2"></i>
                Quản lý tài khoản
            </h3>

            <p>
                Quản lý tài khoản đăng nhập của giáo viên và Ban giám hiệu
            </p>
        </div>

        <div>
            <a
                href="{{ route('admin.accounts.create') }}"
                class="btn btn-light"
            >
                <i class="fa-solid fa-user-plus me-1"></i>
                Thêm tài khoản
            </a>
        </div>

    </div>


    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="fa-solid fa-circle-check me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>
    @endif


    {{-- Thông báo lỗi --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <i class="fa-solid fa-circle-exclamation me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>
    @endif


    {{-- Danh sách tài khoản --}}
    <div class="card">

        <div class="card-body p-0">

            @if($accounts->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>

                                <th class="ps-4">
                                    #
                                </th>

                                <th>
                                    Tài khoản
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Đối tượng
                                </th>

                                <th>
                                    Giáo viên liên kết
                                </th>

                                <th>
                                    Quyền
                                </th>

                                <th class="text-center">
                                    Thao tác
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($accounts as $account)

                                <tr>

                                    {{-- STT --}}
                                    <td class="ps-4">

                                        {{ $accounts->firstItem() + $loop->index }}

                                    </td>


                                    {{-- Tên tài khoản --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="account-avatar me-3">

                                                {{ strtoupper(substr($account->name, 0, 1)) }}

                                            </div>

                                            <div>

                                                <div class="fw-semibold">

                                                    {{ $account->name }}

                                                </div>

                                                <small class="text-muted">

                                                    ID: {{ $account->id }}

                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Email --}}
                                    <td>

                                        {{ $account->email }}

                                    </td>


                                    {{-- Đối tượng --}}
                                    <td>

                                        @if($account->role === 'admin')

                                            <span class="badge bg-danger badge-role">
                                                Quản trị viên
                                            </span>

                                        @elseif($account->role === 'teacher')

                                            <span class="badge bg-primary badge-role">
                                                Giáo viên
                                            </span>

                                        @elseif($account->role === 'bgh')

                                            <span class="badge bg-warning text-dark badge-role">
                                                Ban giám hiệu
                                            </span>

                                        @else

                                            <span class="badge bg-secondary badge-role">
                                                Chưa xác định
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Giáo viên liên kết --}}
                                    <td>

                                        @if($account->teacher)

                                            <div class="fw-semibold">
                                                {{ $account->teacher->full_name }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $account->teacher->teacher_code }}
                                            </small>

                                        @elseif($account->role === 'bgh')

                                            <span class="text-muted">
                                                Không áp dụng
                                            </span>

                                        @elseif($account->role === 'admin')

                                            <span class="text-muted">
                                                Không áp dụng
                                            </span>

                                        @else

                                            <span class="text-danger">
                                                Chưa liên kết
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Quyền --}}
                                    <td>

                                        @if($account->role === 'admin')

                                            <span class="badge bg-danger badge-role">
                                                <i class="fa-solid fa-shield-halved me-1"></i>
                                                Admin
                                            </span>

                                        @elseif($account->role === 'teacher')

                                            <span class="badge bg-primary badge-role">
                                                <i class="fa-solid fa-chalkboard-user me-1"></i>
                                                Giáo viên
                                            </span>

                                        @elseif($account->role === 'bgh')

                                            <span class="badge bg-warning text-dark badge-role">
                                                <i class="fa-solid fa-user-tie me-1"></i>
                                                BGH
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Thao tác --}}
                                    <td>

                                        <div class="action-buttons">

                                            {{-- Xem --}}
                                            <a
                                                href="{{ route('admin.accounts.show', $account) }}"
                                                class="btn btn-sm btn-outline-info"
                                                title="Xem"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                            </a>


                                            {{-- Sửa / phân quyền --}}
                                            <a
                                                href="{{ route('admin.accounts.edit', $account) }}"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Sửa và phân quyền"
                                            >
                                                <i class="fa-solid fa-pen"></i>
                                            </a>


                                            {{-- Xóa --}}
                                            @if($account->id !== auth()->id())

                                                <form
                                                    action="{{ route('admin.accounts.destroy', $account) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?');"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Xóa"
                                                    >
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                </form>

                                            @else

                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    disabled
                                                    title="Không thể xóa tài khoản đang đăng nhập"
                                                >
                                                    <i class="fa-solid fa-lock"></i>
                                                </button>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Phân trang --}}
                <div class="p-3">

                    {{ $accounts->links() }}

                </div>

            @else

                {{-- Không có tài khoản --}}
                <div class="empty-state">

                    <i class="fa-solid fa-user-slash"></i>

                    <h5>
                        Chưa có tài khoản
                    </h5>

                    <p>
                        Hiện tại chưa có tài khoản nào trong hệ thống.
                    </p>

                    <a
                        href="{{ route('admin.accounts.create') }}"
                        class="btn btn-success"
                    >
                        <i class="fa-solid fa-user-plus me-1"></i>
                        Thêm tài khoản đầu tiên
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>