@extends('layouts.app')

@section('title', 'Cập nhật môn học')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Cập nhật môn học
        </h2>

        <p class="text-muted mb-0">
            Chỉnh sửa thông tin môn {{ $subject->subject_name }}
        </p>
    </div>

    <a href="{{ route('subjects.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-warning">

        <h5 class="mb-0">

            <i class="fa-solid fa-pen me-2"></i>

            Cập nhật thông tin môn học

        </h5>

    </div>


    <div class="card-body">

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Vui lòng kiểm tra lại thông tin:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('subjects.update', $subject) }}"
              method="POST">

            @csrf

            @method('PUT')


            <div class="row">

                {{-- Mã môn --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Mã môn <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="subject_code"
                        class="form-control"
                        value="{{ old('subject_code', $subject->subject_code) }}">

                </div>


                {{-- Tên môn --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Tên môn <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="subject_name"
                        class="form-control"
                        value="{{ old('subject_name', $subject->subject_name) }}">

                </div>


                {{-- Giáo viên --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Giáo viên

                    </label>

                    <input
                        type="text"
                        name="teacher"
                        class="form-control"
                        value="{{ old('teacher', $subject->teacher) }}">

                </div>


                {{-- Khối --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">

                        Khối <span class="text-danger">*</span>

                    </label>

                    <select name="grade"
                            class="form-select">

                        <option value="">
                            -- Chọn khối --
                        </option>

                        @for($i = 1; $i <= 5; $i++)

                            <option value="{{ $i }}"
                                {{ old('grade', $subject->grade) == $i ? 'selected' : '' }}>

                                Khối {{ $i }}

                            </option>

                        @endfor

                    </select>

                </div>


                {{-- Trạng thái --}}

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Trạng thái

                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="Đang giảng dạy"
                            {{ old('status', $subject->status) == 'Đang giảng dạy' ? 'selected' : '' }}>

                            Đang giảng dạy

                        </option>

                        <option value="Ngừng giảng dạy"
                            {{ old('status', $subject->status) == 'Ngừng giảng dạy' ? 'selected' : '' }}>

                            Ngừng giảng dạy

                        </option>

                    </select>

                </div>

            </div>


            <hr>


            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-success">

                    <i class="fa-solid fa-save me-1"></i>

                    Cập nhật

                </button>

                <a href="{{ route('subjects.index') }}"
                   class="btn btn-secondary">

                    Hủy

                </a>

            </div>

        </form>

    </div>

</div>

@endsection