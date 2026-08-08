@extends('layouts.app')

@section('title','Cập nhật học sinh')

@section('content')

<div class="card shadow">

    <div class="card-header bg-warning">

        <h4 class="mb-0">
            Cập nhật học sinh
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

        <form action="{{ route('students.update', $student->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label>Mã học sinh</label>

                    <input type="text"
                           name="student_code"
                           class="form-control"
                           value="{{ old('student_code', $student->student_code) }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Họ tên</label>

                    <input type="text"
                           name="full_name"
                           class="form-control"
                           value="{{ old('full_name', $student->full_name) }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Ngày sinh</label>

                    <input type="date"
                           name="date_of_birth"
                           class="form-control"
                           value="{{ old('date_of_birth', $student->date_of_birth) }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Giới tính</label>

                    <select name="gender"
                            class="form-select">

                        <option value="Nam"
                            {{ $student->gender == 'Nam' ? 'selected' : '' }}>
                            Nam
                        </option>

                        <option value="Nữ"
                            {{ $student->gender == 'Nữ' ? 'selected' : '' }}>
                            Nữ
                        </option>

                    </select>

                </div>

                <div class="col-md-12 mb-3">

                    <label>Địa chỉ</label>

                    <textarea
                        class="form-control"
                        rows="3"
                        name="address">{{ old('address', $student->address) }}</textarea>

                </div>

                <div class="col-md-6 mb-3">

                    <label>Email</label>

                    <input type="email"
                           class="form-control"
                           name="email"
                           value="{{ old('email', $student->email) }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Số điện thoại</label>

                    <input type="text"
                           class="form-control"
                           name="phone"
                           value="{{ old('phone', $student->phone) }}">

                </div>

                <div class="col-md-6 mb-4">

                    <label>Trạng thái</label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="Đang học"
                            {{ $student->status == 'Đang học' ? 'selected' : '' }}>
                            Đang học
                        </option>

                        <option value="Nghỉ học"
                            {{ $student->status == 'Nghỉ học' ? 'selected' : '' }}>
                            Nghỉ học
                        </option>

                    </select>

                </div>

            </div>

            <button class="btn btn-success">

                <i class="fa fa-save"></i>

                Cập nhật

            </button>

            <a href="{{ route('students.index') }}"
               class="btn btn-secondary">

                Quay lại

            </a>

        </form>

    </div>

</div>

@endsection