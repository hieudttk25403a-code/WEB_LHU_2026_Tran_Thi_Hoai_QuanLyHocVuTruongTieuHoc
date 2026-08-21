@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- Tiêu đề --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                <i class="fas fa-user-edit text-warning me-2"></i>
                Chỉnh sửa thông tin phụ huynh
            </h3>

            <p class="text-muted mb-0">
                Học sinh:
                <strong>{{ $student->full_name }}</strong>
                - {{ $student->student_code }}
            </p>

        </div>


        <a href="{{ route('students.show', $student->id) }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Quay lại

        </a>

    </div>


    {{-- Lỗi validation --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">

                <i class="fas fa-exclamation-circle me-1"></i>
                Có lỗi xảy ra:

            </div>


            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Thông tin học sinh --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-success text-white">

            <i class="fas fa-user-graduate me-2"></i>
            Thông tin học sinh

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted">
                        Mã học sinh
                    </label>

                    <div class="fw-bold">
                        {{ $student->student_code }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted">
                        Họ và tên
                    </label>

                    <div class="fw-bold">
                        {{ $student->full_name }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted">
                        Ngày sinh
                    </label>

                    <div class="fw-bold">

                        {{ $student->date_of_birth
                            ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y')
                            : 'Chưa cập nhật'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Form --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-warning">

            <i class="fas fa-users me-2"></i>
            Thông tin phụ huynh

        </div>


        <div class="card-body">

            <form
                action="{{ route('student-parents.update', [
                    'student' => $student->id,
                    'parent' => $parent->id
                ]) }}"
                method="POST"
            >

                @csrf

                @method('PUT')


                <div class="row">

                    {{-- Họ tên --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">

                            Họ và tên phụ huynh
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="full_name"
                            value="{{ old('full_name', $parent->full_name) }}"
                            class="form-control @error('full_name') is-invalid @enderror"
                            placeholder="Nhập họ và tên phụ huynh"
                            required
                        >


                        @error('full_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Quan hệ --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">

                            Quan hệ với học sinh
                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="relationship"
                            class="form-select @error('relationship') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Chọn quan hệ --
                            </option>


                            <option value="Cha"
                                {{ old('relationship', $parent->relationship) == 'Cha' ? 'selected' : '' }}>
                                Cha
                            </option>


                            <option value="Mẹ"
                                {{ old('relationship', $parent->relationship) == 'Mẹ' ? 'selected' : '' }}>
                                Mẹ
                            </option>


                            <option value="Ông"
                                {{ old('relationship', $parent->relationship) == 'Ông' ? 'selected' : '' }}>
                                Ông
                            </option>


                            <option value="Bà"
                                {{ old('relationship', $parent->relationship) == 'Bà' ? 'selected' : '' }}>
                                Bà
                            </option>


                            <option value="Người giám hộ"
                                {{ old('relationship', $parent->relationship) == 'Người giám hộ' ? 'selected' : '' }}>
                                Người giám hộ
                            </option>

                        </select>


                        @error('relationship')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Nghề nghiệp --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Nghề nghiệp
                        </label>


                        <input
                            type="text"
                            name="occupation"
                            value="{{ old('occupation', $parent->occupation) }}"
                            class="form-control @error('occupation') is-invalid @enderror"
                            placeholder="Nhập nghề nghiệp"
                        >


                        @error('occupation')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Số điện thoại --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Số điện thoại
                        </label>


                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $parent->phone) }}"
                            class="form-control @error('phone') is-invalid @enderror"
                            placeholder="Nhập số điện thoại"
                        >


                        @error('phone')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Email --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Email
                        </label>


                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $parent->email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="example@gmail.com"
                        >


                        @error('email')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Địa chỉ --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Địa chỉ
                        </label>


                        <input
                            type="text"
                            name="address"
                            value="{{ old('address', $parent->address) }}"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Nhập địa chỉ"
                        >


                        @error('address')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- Nút --}}
                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a href="{{ route('students.show', $student->id) }}"
                       class="btn btn-secondary">

                        <i class="fas fa-times me-1"></i>
                        Hủy

                    </a>


                    <button type="submit"
                            class="btn btn-warning">

                        <i class="fas fa-save me-1"></i>
                        Cập nhật thông tin

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection