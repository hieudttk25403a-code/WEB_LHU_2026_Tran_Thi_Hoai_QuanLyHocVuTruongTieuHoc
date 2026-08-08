@extends('layouts.app')

@section('title','Cập nhật giáo viên')

@section('content')

<div class="card shadow">

    <div class="card-header bg-warning">
        <h4 class="mb-0">Cập nhật giáo viên</h4>
    </div>

    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teachers.update', $teacher) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Mã giáo viên</label>
                    <input type="text"
                           name="teacher_code"
                           class="form-control"
                           value="{{ old('teacher_code', $teacher->teacher_code) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Họ tên</label>
                    <input type="text"
                           name="full_name"
                           class="form-control"
                           value="{{ old('full_name', $teacher->full_name) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Chuyên môn</label>
                    <input type="text"
                           name="specialization"
                           class="form-control"
                           value="{{ old('specialization', $teacher->specialization) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Tổ</label>
                    <input type="text"
                           name="department"
                           class="form-control"
                           value="{{ old('department', $teacher->department) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Số điện thoại</label>
                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="{{ old('phone', $teacher->phone) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $teacher->email) }}">
                </div>

                <div class="col-md-6 mb-4">
                    <label>Trạng thái</label>

                    <select name="status" class="form-select">

                        <option value="Đang công tác"
                            {{ $teacher->status == 'Đang công tác' ? 'selected' : '' }}>
                            Đang công tác
                        </option>

                        <option value="Nghỉ công tác"
                            {{ $teacher->status == 'Nghỉ công tác' ? 'selected' : '' }}>
                            Nghỉ công tác
                        </option>

                    </select>

                </div>

            </div>

            <button class="btn btn-success">
                <i class="fa-solid fa-save"></i>
                Cập nhật
            </button>

            <a href="{{ route('teachers.index') }}"
               class="btn btn-secondary">
                Quay lại
            </a>

        </form>

    </div>

</div>

@endsection