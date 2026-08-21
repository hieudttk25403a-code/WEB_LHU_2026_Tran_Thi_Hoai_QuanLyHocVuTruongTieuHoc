<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Danh sách học sinh
     */
    public function index(Request $request)
    {
        $query = Student::with([
            'schoolClass',
            'classHistories.schoolClass',
            'classHistories.schoolYear',
        ]);

        // Tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', '%' . $keyword . '%')
                    ->orWhere('student_code', 'like', '%' . $keyword . '%')
                    ->orWhere('phone', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        // Lọc theo lớp hiện tại
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        return view('students.index', compact(
            'students',
            'classes'
        ));
    }

    /**
     * Form thêm học sinh
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        $schoolYears = SchoolYear::orderByDesc('id')->get();

        return view('students.create', compact(
            'classes',
            'schoolYears'
        ));
    }

    /**
     * Lưu học sinh
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_code' => 'required|string|max:255|unique:students,student_code',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Nam,Nữ',
            'address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'class_id' => 'nullable|exists:school_classes,id',
            'status' => 'required|string|max:255',

            // Năm học dùng để tạo lịch sử lớp
            'school_year_id' => 'nullable|exists:school_years,id',
        ], [
            'student_code.required' => 'Vui lòng nhập mã học sinh.',
            'student_code.unique' => 'Mã học sinh đã tồn tại.',
            'full_name.required' => 'Vui lòng nhập họ tên học sinh.',
            'date_of_birth.required' => 'Vui lòng nhập ngày sinh.',
            'date_of_birth.date' => 'Ngày sinh không hợp lệ.',
            'gender.required' => 'Vui lòng chọn giới tính.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'email.email' => 'Email không hợp lệ.',
            'class_id.exists' => 'Lớp học không tồn tại.',
            'school_year_id.exists' => 'Năm học không tồn tại.',
        ]);

        DB::transaction(function () use ($validated) {

            $student = Student::create([
                'student_code' => $validated['student_code'],
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'address' => $validated['address'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'class_id' => $validated['class_id'] ?? null,
                'status' => $validated['status'],
            ]);

            /*
             * Nếu lúc thêm học sinh có chọn lớp + năm học
             * thì tự động tạo lịch sử lớp.
             */
            if (
                !empty($validated['class_id']) &&
                !empty($validated['school_year_id'])
            ) {
                $this->createClassHistory(
                    $student,
                    $validated['class_id'],
                    $validated['school_year_id'],
                    'Đang học'
                );

                $this->updateClassStudentCount(
                    $validated['class_id']
                );
            }
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Thêm học sinh thành công!');
    }

    /**
     * Xem chi tiết học sinh
     */
    public function show(Student $student)
    {
        $student->load([
            'schoolClass',

            'classHistories.schoolClass',
            'classHistories.schoolYear',

            'parents',

            'healthProfile',

            'scores.subject',
            'scores.schoolYear',
            'scores.teacher',
        ]);

        return view('students.show', compact('student'));
    }

    /**
     * Form chỉnh sửa học sinh
     */
    public function edit(Student $student)
    {
        $student->load([
            'schoolClass',
            'classHistories.schoolClass',
            'classHistories.schoolYear',
            'parents',
            'healthProfile',
        ]);

        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        $schoolYears = SchoolYear::orderByDesc('id')->get();

        return view('students.edit', compact(
            'student',
            'classes',
            'schoolYears'
        ));
    }

    /**
     * Cập nhật học sinh
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_code' => 'required|string|max:255|unique:students,student_code,' . $student->id,
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Nam,Nữ',
            'address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'class_id' => 'nullable|exists:school_classes,id',
            'status' => 'required|string|max:255',

            'school_year_id' => 'nullable|exists:school_years,id',
        ], [
            'student_code.required' => 'Vui lòng nhập mã học sinh.',
            'student_code.unique' => 'Mã học sinh đã tồn tại.',
            'full_name.required' => 'Vui lòng nhập họ tên học sinh.',
            'date_of_birth.required' => 'Vui lòng nhập ngày sinh.',
            'gender.required' => 'Vui lòng chọn giới tính.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'email.email' => 'Email không hợp lệ.',
            'class_id.exists' => 'Lớp học không tồn tại.',
            'school_year_id.exists' => 'Năm học không tồn tại.',
        ]);

        DB::transaction(function () use ($validated, $student) {

            $oldClassId = $student->class_id;
            $newClassId = $validated['class_id'] ?? null;

            $student->update([
                'student_code' => $validated['student_code'],
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'address' => $validated['address'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'class_id' => $newClassId,
                'status' => $validated['status'],
            ]);

            /*
             * Nếu học sinh đổi lớp và có năm học:
             * tạo một bản ghi lịch sử mới.
             */
            if (
                $newClassId &&
                !empty($validated['school_year_id'])
            ) {
                $currentHistory = $student->classHistories()
                    ->where('school_year_id', $validated['school_year_id'])
                    ->latest('id')
                    ->first();

                /*
                 * Chưa có lịch sử trong năm học này
                 */
                if (!$currentHistory) {

                    $this->createClassHistory(
                        $student,
                        $newClassId,
                        $validated['school_year_id'],
                        'Đang học'
                    );
                }

                /*
                 * Có lịch sử nhưng đổi lớp
                 */
                elseif ((int) $currentHistory->class_id !== (int) $newClassId) {

                    $currentHistory->update([
                        'end_date' => now()->toDateString(),
                        'status' => 'Chuyển lớp',
                        'note' => 'Học sinh được chuyển sang lớp mới trong năm học.',
                    ]);

                    $this->createClassHistory(
                        $student,
                        $newClassId,
                        $validated['school_year_id'],
                        'Đang học'
                    );
                }
            }

            /*
             * Cập nhật sĩ số lớp cũ
             */
            if (
                $oldClassId &&
                $newClassId &&
                (int) $oldClassId !== (int) $newClassId
            ) {
                $this->updateClassStudentCount($oldClassId);
            }

            /*
             * Cập nhật sĩ số lớp mới
             */
            if ($newClassId) {
                $this->updateClassStudentCount($newClassId);
            }
        });

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Cập nhật thông tin học sinh thành công!');
    }

    /**
     * Xóa học sinh
     */
    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {

            $classId = $student->class_id;

            /*
             * Không xóa lịch sử lớp trước khi xóa học sinh.
             * Cascade database sẽ xử lý nếu đã cấu hình.
             */
            $student->delete();

            if ($classId) {
                $this->updateClassStudentCount($classId);
            }
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Xóa học sinh thành công!');
    }

    /**
     * Tạo lịch sử lớp cho học sinh
     */
    private function createClassHistory(
        Student $student,
        int $classId,
        int $schoolYearId,
        string $status = 'Đang học'
    ) {
        /*
         * Không tạo trùng.
         */
        $exists = $student->classHistories()
            ->where('class_id', $classId)
            ->where('school_year_id', $schoolYearId)
            ->exists();

        if ($exists) {
            return;
        }

        $class = SchoolClass::find($classId);

        $student->classHistories()->create([
            'class_id' => $classId,
            'school_year_id' => $schoolYearId,
            'status' => $status,
            'start_date' => now()->toDateString(),
            'note' => $class
                ? 'Học sinh thuộc lớp ' . $class->class_name
                : null,
        ]);
    }

    /**
     * Đồng bộ sĩ số lớp
     */
    private function updateClassStudentCount(int $classId)
    {
        $class = SchoolClass::find($classId);

        if (!$class) {
            return;
        }

        $count = Student::where('class_id', $classId)
            ->whereNotIn('status', [
                'Chuyển trường',
                'Đuổi học',
            ])
            ->count();

        $class->update([
            'student_count' => $count,
        ]);
    }
}