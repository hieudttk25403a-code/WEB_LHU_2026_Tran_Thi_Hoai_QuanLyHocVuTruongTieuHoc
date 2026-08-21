@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="fw-bold mb-1">

        <i class="fas fa-heartbeat text-danger me-2"></i>

        Chỉnh sửa hồ sơ sức khỏe

    </h3>

    <p class="text-muted">

        Học sinh:
        <strong>{{ $student->full_name }}</strong>

    </p>


    <div class="card shadow-sm border-0">

        <div class="card-header bg-warning fw-bold">

            HỒ SƠ SỨC KHỎE

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('student-health.update', $student) }}">

                @csrf

                @method('PUT')


                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Chiều cao (cm)
                        </label>

                        <input type="number"
                               step="0.1"
                               name="height"
                               class="form-control"
                               value="{{ old('height', $health->height) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Cân nặng (kg)
                        </label>

                        <input type="number"
                               step="0.1"
                               name="weight"
                               class="form-control"
                               value="{{ old('weight', $health->weight) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Nhóm máu
                        </label>

                        <select name="blood_group"
                                class="form-select">

                            <option value="">
                                -- Chưa xác định --
                            </option>

                            @foreach(['A', 'B', 'AB', 'O'] as $blood)

                                <option value="{{ $blood }}"
                                    {{ old('blood_group', $health->blood_group) == $blood ? 'selected' : '' }}>

                                    {{ $blood }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Dị ứng
                        </label>

                        <textarea name="allergy"
                                  class="form-control"
                                  rows="3">{{ old('allergy', $health->allergy) }}</textarea>

                    </div>


                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Ghi chú
                        </label>

                        <textarea name="note"
                                  class="form-control"
                                  rows="4">{{ old('note', $health->note) }}</textarea>

                    </div>

                </div>


                <div class="mt-4">

                    <button class="btn btn-success">

                        <i class="fas fa-save me-1"></i>

                        Lưu thay đổi

                    </button>


                    <a href="{{ route('students.show', $student) }}"
                       class="btn btn-secondary">

                        Hủy

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection