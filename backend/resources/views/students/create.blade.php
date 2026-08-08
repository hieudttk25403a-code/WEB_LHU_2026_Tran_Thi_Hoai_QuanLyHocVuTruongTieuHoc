@extends('layouts.app')

@section('title','Thêm học sinh')

@section('content')

<div class="card shadow">

    <div class="card-header bg-primary text-white">

        <h4 class="mb-0">
            Thêm học sinh
        </h4>

    </div>

    <div class="card-body">

        @if ($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form action="{{ route('students.store') }}"
              method="POST">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Mã học sinh
                    </label>

                    <input
                        type="text"
                        name="student_code"
                        class="form-control"
                        value="{{ old('student_code') }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Họ tên
                    </label>

                    <input
                        type="text"
                        name="full_name"
                        class="form-control"
                        value="{{ old('full_name') }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Ngày sinh
                    </label>

                    <input
                        type="date"
                        name="date_of_birth"
                        class="form-control"
                        value="{{ old('date_of_birth') }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Giới tính
                    </label>

                    <select
                        name="gender"
                        class="form-select">

                        <option value="">-- Chọn --</option>

                        <option value="Nam">Nam</option>

                        <option value="Nữ">Nữ</option>

                    </select>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Địa chỉ
                    </label>

                    <textarea
                        name="address"
                        rows="3"
                        class="form-control">{{ old('address') }}</textarea>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Số điện thoại
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone') }}">

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">
                        Trạng thái
                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="Đang học">Đang học</option>

                        <option value="Nghỉ học">Nghỉ học</option>

                    </select>

                </div>

            </div>

            <button class="btn btn-success">

                <i class="fa fa-save"></i>

                Lưu học sinh

            </button>

            <a href="{{ route('students.index') }}"
               class="btn btn-secondary">

                Quay lại

            </a>

        </form>

    </div>

</div>

@endsection