<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Thêm tài khoản - Quản lý học vụ</title>

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

        .form-label {
            font-weight: 600;
        }

        .required {
            color: #dc3545;
        }

        .teacher-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            display: none;
        }

        .readonly-field {
            background-color: #f1f3f5 !important;
        }

    </style>

</head>


<body>

<div class="container-fluid py-4 px-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="page-header">

        <h3>
            <i class="fa-solid fa-user-plus me-2"></i>
            Thêm tài khoản
        </h3>

        <p>
            Cấp tài khoản đăng nhập cho giáo viên hoặc Ban giám hiệu
        </p>

    </div>


    {{-- =========================================================
         HIỂN THỊ LỖI VALIDATION
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                <i class="fa-solid fa-circle-exclamation me-2"></i>

                Vui lòng kiểm tra lại thông tin:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         FORM
    ========================================================== --}}

    <div class="card">

        <div class="card-body p-4">

            <form
                action="{{ route('admin.accounts.store') }}"
                method="POST"
                autocomplete="off"
            >

                @csrf


                {{-- =================================================
                     LOẠI TÀI KHOẢN
                ================================================== --}}

                <div class="mb-4">

                    <label
                        for="role"
                        class="form-label"
                    >

                        Loại tài khoản

                        <span class="required">*</span>

                    </label>


                    <select
                        name="role"
                        id="role"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Chọn loại tài khoản --
                        </option>


                        <option
                            value="teacher"
                            {{ old('role') === 'teacher' ? 'selected' : '' }}
                        >
                            Giáo viên
                        </option>


                        <option
                            value="bgh"
                            {{ old('role') === 'bgh' ? 'selected' : '' }}
                        >
                            Ban giám hiệu
                        </option>

                    </select>

                </div>


                {{-- =================================================
                     CHỌN GIÁO VIÊN
                ================================================== --}}

                <div
                    class="mb-4"
                    id="teacher-section"
                >

                    <label
                        for="teacher_id"
                        class="form-label"
                    >

                        Giáo viên

                        <span class="required">*</span>

                    </label>


                    <select
                        name="teacher_id"
                        id="teacher_id"
                        class="form-select"
                    >

                        <option value="">
                            -- Chọn giáo viên --
                        </option>


                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                data-name="{{ $teacher->full_name }}"
                                data-email="{{ $teacher->email ?? '' }}"
                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}
                            >

                                {{ $teacher->teacher_code }}
                                -
                                {{ $teacher->full_name }}

                            </option>

                        @endforeach

                    </select>


                    <small class="text-muted">

                        Chỉ hiển thị giáo viên chưa được cấp tài khoản.

                    </small>

                </div>


                {{-- =================================================
                     HỌ VÀ TÊN
                ================================================== --}}

                <div class="mb-4">

                    <label
                        for="name"
                        class="form-label"
                    >

                        Họ và tên

                        <span class="required">*</span>

                    </label>


                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        placeholder="Nhập họ và tên"
                        autocomplete="off"
                        required
                    >

                </div>


                {{-- =================================================
                     EMAIL
                ================================================== --}}

                <div class="mb-4">

                    <label
                        for="email"
                        class="form-label"
                    >

                        Email đăng nhập

                        <span class="required">*</span>

                    </label>


                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="Nhập email"
                        autocomplete="off"
                        required
                    >


                    <small
                        id="email-help"
                        class="text-muted"
                    >

                        Đối với giáo viên, email sẽ được lấy tự động
                        từ hồ sơ giáo viên.

                    </small>

                </div>


                {{-- =================================================
                     MẬT KHẨU
                ================================================== --}}

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label
                            for="password"
                            class="form-label"
                        >

                            Mật khẩu

                            <span class="required">*</span>

                        </label>


                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Tối thiểu 8 ký tự"
                            autocomplete="new-password"
                            required
                        >

                    </div>


                    {{-- =================================================
                         XÁC NHẬN MẬT KHẨU
                    ================================================== --}}

                    <div class="col-md-6 mb-4">

                        <label
                            for="password_confirmation"
                            class="form-label"
                        >

                            Xác nhận mật khẩu

                            <span class="required">*</span>

                        </label>


                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-control"
                            placeholder="Nhập lại mật khẩu"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                </div>


                {{-- =================================================
                     THÔNG TIN LIÊN KẾT GIÁO VIÊN
                ================================================== --}}

                <div
                    id="teacher-info"
                    class="teacher-info mb-4"
                >

                    <div class="fw-semibold mb-2">

                        <i
                            class="fa-solid fa-circle-info me-2 text-success">
                        </i>

                        Thông tin tài khoản giáo viên

                    </div>


                    <div id="teacher-info-content"></div>

                </div>


                {{-- =================================================
                     BUTTON
                ================================================== --}}

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.accounts.index') }}"
                        class="btn btn-secondary"
                    >

                        <i class="fa-solid fa-arrow-left me-1"></i>

                        Hủy

                    </a>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="fa-solid fa-user-plus me-1"></i>

                        Tạo tài khoản

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | LẤY CÁC ELEMENT
    |--------------------------------------------------------------------------
    */

    const roleSelect =
        document.getElementById('role');

    const teacherSection =
        document.getElementById('teacher-section');

    const teacherSelect =
        document.getElementById('teacher_id');

    const nameInput =
        document.getElementById('name');

    const emailInput =
        document.getElementById('email');

    const emailHelp =
        document.getElementById('email-help');

    const teacherInfo =
        document.getElementById('teacher-info');

    const teacherInfoContent =
        document.getElementById('teacher-info-content');


    /*
    |--------------------------------------------------------------------------
    | HIỂN THỊ FORM THEO LOẠI TÀI KHOẢN
    |--------------------------------------------------------------------------
    */

    function updateAccountType() {

        const role =
            roleSelect.value;


        /*
        |--------------------------------------------------------------------------
        | GIÁO VIÊN
        |--------------------------------------------------------------------------
        */

        if (role === 'teacher') {

            teacherSection.style.display = 'block';

            teacherSelect.required = true;

            /*
            | Họ tên và email của giáo viên sẽ lấy tự động
            */

            nameInput.readOnly = true;

            emailInput.readOnly = true;

            nameInput.classList.add('readonly-field');

            emailInput.classList.add('readonly-field');

            emailHelp.innerText =
                'Email đăng nhập được lấy tự động từ hồ sơ giáo viên.';


            /*
            | Nếu đã chọn giáo viên thì cập nhật thông tin
            */

            updateTeacherInfo();

        }


        /*
        |--------------------------------------------------------------------------
        | BAN GIÁM HIỆU
        |--------------------------------------------------------------------------
        */

        else if (role === 'bgh') {

            teacherSection.style.display = 'none';

            teacherSelect.required = false;

            teacherSelect.value = '';

            /*
            | BGH tự nhập tên và email
            */

            nameInput.readOnly = false;

            emailInput.readOnly = false;

            nameInput.classList.remove('readonly-field');

            emailInput.classList.remove('readonly-field');

            nameInput.value = '';

            emailInput.value = '';

            nameInput.placeholder =
                'Nhập họ và tên thành viên Ban giám hiệu';

            emailInput.placeholder =
                'Nhập email riêng của thành viên Ban giám hiệu';

            emailHelp.innerText =
                'Nhập email riêng để sử dụng làm tài khoản đăng nhập.';

            teacherInfo.style.display = 'none';

        }


        /*
        |--------------------------------------------------------------------------
        | CHƯA CHỌN LOẠI TÀI KHOẢN
        |--------------------------------------------------------------------------
        */

        else {

            teacherSection.style.display = 'none';

            teacherSelect.required = false;

            teacherSelect.value = '';

            nameInput.readOnly = false;

            emailInput.readOnly = false;

            nameInput.classList.remove('readonly-field');

            emailInput.classList.remove('readonly-field');

            nameInput.value = '';

            emailInput.value = '';

            nameInput.placeholder =
                'Nhập họ và tên';

            emailInput.placeholder =
                'Nhập email';

            emailHelp.innerText =
                'Đối với giáo viên, email sẽ được lấy tự động từ hồ sơ giáo viên.';

            teacherInfo.style.display = 'none';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LẤY THÔNG TIN GIÁO VIÊN
    |--------------------------------------------------------------------------
    */

    function updateTeacherInfo() {

        /*
        | Chỉ xử lý khi đang chọn Giáo viên
        */

        if (roleSelect.value !== 'teacher') {

            return;

        }


        const selectedOption =
            teacherSelect.options[
                teacherSelect.selectedIndex
            ];


        /*
        | Chưa chọn giáo viên
        */

        if (
            !teacherSelect.value ||
            !selectedOption
        ) {

            nameInput.value = '';

            emailInput.value = '';

            teacherInfo.style.display = 'none';

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | LẤY DATA TỪ OPTION
        |--------------------------------------------------------------------------
        */

        const teacherName =
            selectedOption.getAttribute('data-name') || '';

        const teacherEmail =
            selectedOption.getAttribute('data-email') || '';


        /*
        |--------------------------------------------------------------------------
        | ĐIỀN TỰ ĐỘNG
        |--------------------------------------------------------------------------
        */

        nameInput.value =
            teacherName;

        emailInput.value =
            teacherEmail;


        /*
        |--------------------------------------------------------------------------
        | HIỂN THỊ THÔNG TIN
        |--------------------------------------------------------------------------
        */

        if (teacherEmail) {

            teacherInfoContent.innerHTML =

                '<div class="mb-1">' +

                    '<strong>Giáo viên:</strong> ' +

                    teacherName +

                '</div>' +

                '<div>' +

                    '<strong>Email đăng nhập:</strong> ' +

                    teacherEmail +

                '</div>' +

                '<div class="text-muted mt-2">' +

                    '<i class="fa-solid fa-link me-1"></i>' +

                    'Tài khoản sẽ được liên kết với hồ sơ giáo viên này.' +

                '</div>';


            teacherInfo.style.display =
                'block';

        }

        else {

            /*
            | Giáo viên chưa có email
            */

            teacherInfoContent.innerHTML =

                '<div class="text-danger">' +

                    '<i class="fa-solid fa-triangle-exclamation me-2"></i>' +

                    '<strong>Giáo viên này chưa có email.</strong>' +

                    '<br>' +

                    'Vui lòng cập nhật email trong ' +

                    '<strong>Quản lý giáo viên</strong> ' +

                    'trước khi cấp tài khoản.' +

                '</div>';


            teacherInfo.style.display =
                'block';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | KHI CHỌN GIÁO VIÊN
    |--------------------------------------------------------------------------
    */

    teacherSelect.addEventListener(
        'change',
        function () {

            updateTeacherInfo();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | KHI CHỌN LOẠI TÀI KHOẢN
    |--------------------------------------------------------------------------
    */

    roleSelect.addEventListener(
        'change',
        function () {

            updateAccountType();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | KHỞI TẠO FORM
    |--------------------------------------------------------------------------
    */

    updateAccountType();

});

</script>

</body>

</html>