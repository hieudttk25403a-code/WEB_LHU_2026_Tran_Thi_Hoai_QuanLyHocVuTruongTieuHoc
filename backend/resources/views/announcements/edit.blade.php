@extends('layouts.app')

@section('title', 'Sửa thông báo')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">
            Sửa thông báo
        </h2>

        <p class="text-muted mb-0">
            Cập nhật thông tin thông báo
        </p>

    </div>

    <a href="{{ route('announcements.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Quay lại

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-warning">

        <h5 class="mb-0">

            <i class="fa-solid fa-pen me-2"></i>

            Cập nhật thông báo

        </h5>

    </div>


    <div class="card-body">

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Vui lòng kiểm tra lại:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('announcements.update', $announcement) }}"
              method="POST">

            @csrf

            @method('PUT')


            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Tiêu đề
                    <span class="text-danger">*</span>

                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $announcement->title) }}"
                    placeholder="Nhập tiêu đề thông báo...">

            </div>


            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Nội dung
                    <span class="text-danger">*</span>

                </label>

                <textarea
                    name="content"
                    rows="8"
                    class="form-control"
                    placeholder="Nhập nội dung thông báo...">{{ old('content', $announcement->content) }}</textarea>

            </div>


            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Người tạo
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $announcement->creator->name ?? 'N/A' }}"
                    disabled>

            </div>


            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Trạng thái
                </label>

                <div class="form-check">

                    <input
                        type="checkbox"
                        name="is_published"
                        value="1"
                        class="form-check-input"
                        id="is_published"
                        {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>

                    <label
                        class="form-check-label"
                        for="is_published">

                        Hiển thị thông báo

                    </label>

                </div>

            </div>


            <hr>


            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-success">

                    <i class="fa-solid fa-save me-1"></i>

                    Cập nhật

                </button>


                <a href="{{ route('announcements.index') }}"
                   class="btn btn-secondary">

                    Hủy

                </a>

            </div>

        </form>

    </div>

</div>

@endsection