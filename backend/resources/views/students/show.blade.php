@extends('layouts.app')

@section('title', 'Chi tiết học sinh')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Chi tiết học sinh
        </h2>

        <p class="text-muted mb-0">
            Thông tin chi tiết và hồ sơ của học sinh
        </p>
    </div>

    <div>

        <a href="{{ route('students.index') }}"
           class="btn btn-secondary">

            <i class="fa-solid fa-arrow-left me-1"></i>

            Quay lại

        </a>

        <a href="{{ route('students.edit', $student) }}"
           class="btn btn-warning">

            <i class="fa-solid fa-pen me-1"></i>

            Chỉnh sửa

        </a>

    </div>

</div>


{{-- ===================================================== --}}
{{-- THÔNG TIN HỌC SINH --}}
{{-- ===================================================== --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-user-graduate me-2"></i>

            Thông tin học sinh

        </h5>

    </div>


    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-6">

                <small class="text-muted">
                    Mã học sinh
                </small>

                <div class="fw-semibold fs-5">
                    {{ $student->student_code }}
                </div>

            </div>


            <div class="col-md-6">

                <small class="text-muted">
                    Họ và tên
                </small>

                <div class="fw-semibold fs-5">
                    {{ $student->full_name }}
                </div>

            </div>


            <div class="col-md-4">

                <small class="text-muted">
                    Ngày sinh
                </small>

                <div class="fw-semibold">

                    {{ $student->date_of_birth
                        ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y')
                        : '-' }}

                </div>

            </div>


            <div class="col-md-4">

                <small class="text-muted">
                    Giới tính
                </small>

                <div class="fw-semibold">
                    {{ $student->gender ?? '-' }}
                </div>

            </div>


            <div class="col-md-4">

                <small class="text-muted">
                    Trạng thái
                </small>

                <div>

                    @if($student->status)

                        <span class="badge bg-success">
                            {{ $student->status }}
                        </span>

                    @else

                        <span class="text-muted">
                            -
                        </span>

                    @endif

                </div>

            </div>


            <div class="col-md-6">

                <small class="text-muted">
                    Email
                </small>

                <div class="fw-semibold">
                    {{ $student->email ?? '-' }}
                </div>

            </div>


            <div class="col-md-6">

                <small class="text-muted">
                    Số điện thoại
                </small>

                <div class="fw-semibold">
                    {{ $student->phone ?? '-' }}
                </div>

            </div>


            <div class="col-12">

                <small class="text-muted">
                    Địa chỉ
                </small>

                <div class="fw-semibold">
                    {{ $student->address ?? '-' }}
                </div>

            </div>

        </div>

    </div>

</div>


{{-- ===================================================== --}}
{{-- THÔNG TIN PHỤ HUYNH --}}
{{-- ===================================================== --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-success text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-people-roof me-2"></i>

            Thông tin phụ huynh

        </h5>

    </div>


    <div class="card-body">

        @if($student->parents && $student->parents->count() > 0)

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Họ tên
                            </th>

                            <th>
                                Quan hệ
                            </th>

                            <th>
                                Nghề nghiệp
                            </th>

                            <th>
                                Số điện thoại
                            </th>

                            <th>
                                Email
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($student->parents as $parent)

                            <tr>

                                <td class="fw-semibold">

                                    {{ $parent->full_name }}

                                </td>


                                <td>

                                    {{ $parent->relationship }}

                                </td>


                                <td>

                                    {{ $parent->occupation ?? '-' }}

                                </td>


                                <td>

                                    {{ $parent->phone ?? '-' }}

                                </td>


                                <td>

                                    {{ $parent->email ?? '-' }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="text-center text-muted py-4">

                <i class="fa-solid fa-users fa-2x mb-3"></i>

                <p class="mb-0">
                    Chưa có thông tin phụ huynh.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- ===================================================== --}}
{{-- HỒ SƠ SỨC KHỎE --}}
{{-- ===================================================== --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-danger text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-heart-pulse me-2"></i>

            Hồ sơ sức khỏe

        </h5>

    </div>


    <div class="card-body">

        {{-- Hiển thị lỗi --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Có lỗi xảy ra:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Thông báo thành công --}}

        @if(session('success'))

            <div class="alert alert-success">

                <i class="fa-solid fa-circle-check me-2"></i>

                {{ session('success') }}

            </div>

        @endif


        <form
            action="{{ route('students.health.store', $student) }}"
            method="POST">

            @csrf


            <div class="row">


                {{-- Chiều cao --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Chiều cao (cm)

                    </label>

                    <input
                        type="number"
                        name="height"
                        class="form-control"
                        step="0.01"
                        min="0"
                        max="300"
                        placeholder="Ví dụ: 125"
                        value="{{ old('height', optional($student->health)->height) }}">

                </div>


                {{-- Cân nặng --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Cân nặng (kg)

                    </label>

                    <input
                        type="number"
                        name="weight"
                        class="form-control"
                        step="0.01"
                        min="0"
                        max="500"
                        placeholder="Ví dụ: 25"
                        value="{{ old('weight', optional($student->health)->weight) }}">

                </div>


                {{-- Nhóm máu --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Nhóm máu

                    </label>

                    <select
                        name="blood_type"
                        class="form-select">

                        <option value="">
                            -- Chọn nhóm máu --
                        </option>

                        <option value="A"
                            {{ old('blood_type', optional($student->health)->blood_type) == 'A' ? 'selected' : '' }}>
                            A
                        </option>

                        <option value="B"
                            {{ old('blood_type', optional($student->health)->blood_type) == 'B' ? 'selected' : '' }}>
                            B
                        </option>

                        <option value="AB"
                            {{ old('blood_type', optional($student->health)->blood_type) == 'AB' ? 'selected' : '' }}>
                            AB
                        </option>

                        <option value="O"
                            {{ old('blood_type', optional($student->health)->blood_type) == 'O' ? 'selected' : '' }}>
                            O
                        </option>

                    </select>

                </div>


                {{-- Dị ứng --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Dị ứng

                    </label>

                    <input
                        type="text"
                        name="allergies"
                        class="form-control"
                        placeholder="Ví dụ: Dị ứng hải sản"
                        value="{{ old('allergies', optional($student->health)->allergies) }}">

                </div>


                {{-- Ghi chú --}}

                <div class="col-12 mb-3">

                    <label class="form-label fw-semibold">

                        Ghi chú

                    </label>

                    <textarea
                        name="notes"
                        class="form-control"
                        rows="4"
                        placeholder="Nhập ghi chú về sức khỏe học sinh...">{{ old('notes', optional($student->health)->notes) }}</textarea>

                </div>

            </div>


            <hr>


            <div class="d-flex justify-content-end">

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="fa-solid fa-save me-1"></i>

                    Lưu hồ sơ sức khỏe

                </button>

            </div>

        </form>

    </div>

</div>


@endsection