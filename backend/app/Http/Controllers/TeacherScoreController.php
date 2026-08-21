<?php

namespace App\Http\Controllers;

use App\Models\ClassAssignment;
use App\Models\Score;
use App\Models\ScoreEditHistory;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentAcademicResult;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeacherScoreController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CÁC CỘT ĐIỂM
    |--------------------------------------------------------------------------
    */

    private array $scoreFields = [
        'oral_score',
        'fifteen_minute_score',
        'midterm_score',
        'final_score',
    ];


    /*
    |--------------------------------------------------------------------------
    | TRANG QUẢN LÝ ĐIỂM CỦA GIÁO VIÊN
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->isTeacher()) {
            abort(
                403,
                'Chức năng này chỉ dành cho giáo viên.'
            );
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            abort(
                403,
                'Tài khoản chưa được liên kết với hồ sơ giáo viên.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NĂM HỌC ĐANG HOẠT ĐỘNG
        |--------------------------------------------------------------------------
        */

        $activeYear = SchoolYear::where(
            'is_active',
            true
        )->first();


        /*
        |--------------------------------------------------------------------------
        | PHÂN CÔNG GIẢNG DẠY
        |--------------------------------------------------------------------------
        */

        $assignments = TeacherSubjectAssignment::with([
            'subject',
            'schoolClass',
            'schoolYear',
        ])
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->when(
                $activeYear,
                function ($query) use ($activeYear) {
                    $query->where(
                        'school_year_id',
                        $activeYear->id
                    );
                }
            )
            ->orderBy('class_id')
            ->orderBy('subject_id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PHÂN CÔNG ĐANG CHỌN
        |--------------------------------------------------------------------------
        */

        $selectedAssignment = null;

        if ($request->filled('assignment_id')) {

            $selectedAssignment =
                $assignments->firstWhere(
                    'id',
                    (int) $request->assignment_id
                );
        }

        if (
            !$selectedAssignment
            && $assignments->isNotEmpty()
        ) {
            $selectedAssignment =
                $assignments->first();
        }


        /*
        |--------------------------------------------------------------------------
        | HỌC SINH + ĐIỂM CỦA MÔN ĐANG DẠY
        |--------------------------------------------------------------------------
        */

        $students = collect();

        $scores = collect();

        if ($selectedAssignment) {

            $students = Student::with(
                'schoolClass'
            )
                ->where(
                    'class_id',
                    $selectedAssignment->class_id
                )
                ->orderBy('full_name')
                ->get();


            if ($students->isNotEmpty()) {

                $scores = Score::where(
                    'subject_id',
                    $selectedAssignment->subject_id
                )
                    ->where(
                        'school_year_id',
                        $selectedAssignment->school_year_id
                    )
                    ->whereIn(
                        'student_id',
                        $students->pluck('id')
                    )
                    ->with('editHistories')
                    ->get()
                    ->keyBy('student_id');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | LỚP CHỦ NHIỆM
        |--------------------------------------------------------------------------
        */

        $homeroomClasses = ClassAssignment::with([
            'schoolClass',
            'schoolYear',
        ])
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->when(
                $activeYear,
                function ($query) use ($activeYear) {
                    $query->where(
                        'school_year_id',
                        $activeYear->id
                    );
                }
            )
            ->orderBy('class_id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TẤT CẢ MÔN HỌC
        |--------------------------------------------------------------------------
        */

        $allSubjects = Subject::orderBy(
            'subject_name'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | TỔNG HỢP LỚP CHỦ NHIỆM
        |--------------------------------------------------------------------------
        */

        $homeroomData = collect();

        $academicResultTableExists =
            Schema::hasTable(
                'student_academic_results'
            );


        foreach (
            $homeroomClasses
            as $classAssignment
        ) {

            $class =
                $classAssignment->schoolClass;

            if (!$class) {
                continue;
            }


            $classStudents = Student::where(
                'class_id',
                $class->id
            )
                ->orderBy('full_name')
                ->get();


            $classStudentData = collect();


            foreach (
                $classStudents
                as $student
            ) {

                /*
                |--------------------------------------------------------------------------
                | ĐIỂM CỦA TẤT CẢ CÁC MÔN
                |--------------------------------------------------------------------------
                */

                $studentScores = Score::with(
                    'subject'
                )
                    ->where(
                        'student_id',
                        $student->id
                    )
                    ->where(
                        'school_year_id',
                        $classAssignment->school_year_id
                    )
                    ->get()
                    ->keyBy('subject_id');


                $subjectAverages = [];

                $allSubjectsComplete = true;


                foreach (
                    $allSubjects
                    as $subject
                ) {

                    $score =
                        $studentScores->get(
                            $subject->id
                        );


                    if (!$score) {

                        $allSubjectsComplete = false;

                        $subjectAverages[
                            $subject->id
                        ] = null;

                        continue;
                    }


                    $average =
                        $this->calculateCompleteAverage(
                            $score
                        );


                    $subjectAverages[
                        $subject->id
                    ] = $average;


                    if ($average === null) {

                        $allSubjectsComplete = false;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | TB TẤT CẢ CÁC MÔN
                |--------------------------------------------------------------------------
                */

                $overallAverage = null;


                if (
                    $allSubjectsComplete
                    && $allSubjects->count() > 0
                ) {

                    $validAverages =
                        collect(
                            $subjectAverages
                        )
                            ->filter(
                                function ($value) {
                                    return $value !== null;
                                }
                            )
                            ->values();


                    if (
                        $validAverages->count()
                        === $allSubjects->count()
                    ) {

                        $overallAverage =
                            round(
                                $validAverages->avg(),
                                2
                            );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | KẾT QUẢ ĐÃ LƯU
                |--------------------------------------------------------------------------
                */

                $homeroomResult = null;


                if ($academicResultTableExists) {

                    $homeroomResult =
                        StudentAcademicResult::where(
                            'student_id',
                            $student->id
                        )
                            ->where(
                                'school_year_id',
                                $classAssignment->school_year_id
                            )
                            ->first();
                }


                $classStudentData->push([

                    'student' =>
                        $student,

                    'scores' =>
                        $studentScores,

                    'subject_averages' =>
                        $subjectAverages,

                    'overall_average' =>
                        $overallAverage,

                    'conduct' =>
                        $homeroomResult->conduct
                        ?? null,

                    'classification' =>
                        $homeroomResult->classification
                        ?? null,

                    'result_id' =>
                        $homeroomResult->id
                        ?? null,
                ]);
            }


            $homeroomData->push([

                'assignment' =>
                    $classAssignment,

                'class' =>
                    $class,

                'students' =>
                    $classStudentData,
            ]);
        }


        return view(
            'teachers.scores.index',
            compact(
                'teacher',
                'activeYear',
                'assignments',
                'selectedAssignment',
                'students',
                'scores',
                'homeroomClasses',
                'homeroomData',
                'allSubjects'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LƯU / NHẬP ĐIỂM
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();


        if (!$user || !$user->isTeacher()) {

            abort(
                403,
                'Bạn không có quyền nhập điểm.'
            );
        }


        $teacher = $user->teacher;


        if (!$teacher) {

            abort(
                403,
                'Tài khoản chưa được liên kết với giáo viên.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'assignment_id' =>
                'required|integer|exists:teacher_subject_assignments,id',

            'scores' =>
                'nullable|array',

            'scores.*.oral_score' =>
                'nullable|numeric|min:0|max:10',

            'scores.*.fifteen_minute_score' =>
                'nullable|numeric|min:0|max:10',

            'scores.*.midterm_score' =>
                'nullable|numeric|min:0|max:10',

            'scores.*.final_score' =>
                'nullable|numeric|min:0|max:10',

            'scores.*.note' =>
                'nullable|string|max:2000',
        ]);


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA PHÂN CÔNG
        |--------------------------------------------------------------------------
        */

        $assignment =
            TeacherSubjectAssignment::where(
                'id',
                $request->assignment_id
            )
                ->where(
                    'teacher_id',
                    $teacher->id
                )
                ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | HỌC SINH CỦA LỚP
        |--------------------------------------------------------------------------
        */

        $students = Student::where(
            'class_id',
            $assignment->class_id
        )
            ->get()
            ->keyBy('id');


        try {

            DB::transaction(
                function () use (
                    $request,
                    $assignment,
                    $teacher,
                    $students
                ) {

                    foreach (
                        $request->input(
                            'scores',
                            []
                        ) as $studentId => $data
                    ) {

                        /*
                        | Không cho sửa học sinh ngoài lớp.
                        */

                        if (
                            !$students->has(
                                $studentId
                            )
                        ) {
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | TÌM BẢNG ĐIỂM
                        |--------------------------------------------------------------------------
                        */

                        $score =
                            Score::where(
                                'student_id',
                                $studentId
                            )
                                ->where(
                                    'subject_id',
                                    $assignment->subject_id
                                )
                                ->where(
                                    'school_year_id',
                                    $assignment->school_year_id
                                )
                                ->first();


                        $isExisting =
                            $score !== null;


                        /*
                        |--------------------------------------------------------------------------
                        | NẾU CHƯA CÓ THÌ TẠO
                        |--------------------------------------------------------------------------
                        */

                        if (!$score) {

                            $score =
                                new Score();

                            $score->student_id =
                                $studentId;

                            $score->subject_id =
                                $assignment->subject_id;

                            $score->school_year_id =
                                $assignment->school_year_id;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | KIỂM TRA CÁC CỘT BỊ THAY ĐỔI
                        |--------------------------------------------------------------------------
                        */

                        $changedFields = [];


                        foreach (
                            $this->scoreFields
                            as $field
                        ) {

                            if (
                                !array_key_exists(
                                    $field,
                                    $data
                                )
                            ) {
                                continue;
                            }


                            $oldValue =
                                $score->{$field};

                            $newValue =
                                $data[$field];


                            if (
                                $newValue === ''
                            ) {

                                $newValue =
                                    null;
                            }


                            if (
                                !$this->sameScoreValue(
                                    $oldValue,
                                    $newValue
                                )
                            ) {

                                $changedFields[
                                    $field
                                ] = [

                                    'old' =>
                                        $oldValue,

                                    'new' =>
                                        $newValue,
                                ];
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | KIỂM TRA GIỚI HẠN 3 LẦN SỬA
                        |--------------------------------------------------------------------------
                        |
                        | 1 lần bấm LƯU = 1 lần sửa.
                        |
                        | Không tính theo số cột.
                        |
                        */

                        if (
                            $isExisting
                            && count(
                                $changedFields
                            ) > 0
                        ) {

                            $totalEdits =
                                ScoreEditHistory::where(
                                    'score_id',
                                    $score->id
                                )->count();


                            if (
                                $totalEdits >= 3
                            ) {

                                throw new \RuntimeException(
                                    'Học sinh này đã sử dụng đủ 3 lần chỉnh sửa điểm. Vui lòng báo Quản trị viên để được xử lý.'
                                );
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | GHI 1 LỊCH SỬ CHO 1 LẦN LƯU
                            |--------------------------------------------------------------------------
                            */

                            $changeDescription = [];


                            foreach (
                                $changedFields
                                as $field => $change
                            ) {

                                $old =
                                    $change['old'] === null
                                        ? 'trống'
                                        : $change['old'];

                                $new =
                                    $change['new'] === null
                                        ? 'trống'
                                        : $change['new'];


                                $changeDescription[] =
                                    $this->scoreFieldLabel(
                                        $field
                                    )
                                    . ': '
                                    . $old
                                    . ' → '
                                    . $new;
                            }


                            ScoreEditHistory::create([

                                'score_id' =>
                                    $score->id,

                                'user_id' =>
                                    Auth::id(),

                                'score_type' =>
                                    'batch_edit',

                                'old_value' =>
                                    null,

                                'new_value' =>
                                    null,

                                'note' =>
                                    'Lần sửa '
                                    . ($totalEdits + 1)
                                    . '/3. '
                                    . implode(
                                        '; ',
                                        $changeDescription
                                    ),
                            ]);
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | GÁN GIÁ TRỊ ĐIỂM
                        |--------------------------------------------------------------------------
                        */

                        foreach (
                            $this->scoreFields
                            as $field
                        ) {

                            if (
                                !array_key_exists(
                                    $field,
                                    $data
                                )
                            ) {
                                continue;
                            }


                            $value =
                                $data[$field];


                            if (
                                $value === ''
                            ) {

                                $value =
                                    null;
                            }


                            $score->{$field} =
                                $value;
                        }




                        /*
                        |--------------------------------------------------------------------------
                        | GHI CHÚ
                        |--------------------------------------------------------------------------
                        */

                        if (
                            array_key_exists(
                                'note',
                                $data
                            )
                        ) {

                            $score->note =
                                $data['note'];
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | TÍNH TRUNG BÌNH MÔN
                        |--------------------------------------------------------------------------
                        |
                        | Chỉ tính khi đủ cả:
                        |
                        | Miệng
                        | 15 phút
                        | Giữa kỳ
                        | Cuối kỳ
                        |
                        */

                        $score->average_score =
                            $this->calculateCompleteAverage(
                                $score
                            );


                        $score->save();
                    }
                }
            );

        } catch (
            \RuntimeException $e
        ) {

            return redirect()
                ->route(
                    'teacher.scores.index',
                    [
                        'assignment_id' =>
                            $assignment->id,
                    ]
                )
                ->with(
                    'error',
                    $e->getMessage()
                );
        }


        return redirect()
            ->route(
                'teacher.scores.index',
                [
                    'assignment_id' =>
                        $assignment->id,
                ]
            )
            ->with(
                'success',
                'Đã lưu điểm thành công.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GVCN LƯU HẠNH KIỂM + KẾT QUẢ HỌC TẬP
    |--------------------------------------------------------------------------
    */

    public function saveHomeroomResult(
        Request $request
    ) {

        $user = Auth::user();


        if (
            !$user
            || !$user->isTeacher()
        ) {

            abort(
                403,
                'Bạn không có quyền thực hiện chức năng này.'
            );
        }


        $teacher = $user->teacher;


        if (!$teacher) {

            abort(
                403,
                'Tài khoản chưa được liên kết với giáo viên.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'class_assignment_id' =>
                'required|integer|exists:class_assignments,id',

            'student_id' =>
                'required|integer|exists:students,id',

            'conduct' =>
                'required|in:Tốt,Đạt,Cần cố gắng',
        ]);


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA GVCN
        |--------------------------------------------------------------------------
        */

        $classAssignment =
            ClassAssignment::findOrFail(
                $request->class_assignment_id
            );


        if (
            (int) $classAssignment->teacher_id
            !== (int) $teacher->id
        ) {

            abort(
                403,
                'Bạn không phải giáo viên chủ nhiệm của lớp này.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA HỌC SINH
        |--------------------------------------------------------------------------
        */

        $student =
            Student::findOrFail(
                $request->student_id
            );


        if (
            (int) $student->class_id
            !== (int) $classAssignment->class_id
        ) {

            abort(
                403,
                'Học sinh không thuộc lớp chủ nhiệm.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA BẢNG KẾT QUẢ
        |--------------------------------------------------------------------------
        */

        if (
            !Schema::hasTable(
                'student_academic_results'
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Chưa có bảng student_academic_results. Hãy chạy migration trước.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | LẤY TẤT CẢ MÔN
        |--------------------------------------------------------------------------
        */

        $subjects =
            Subject::orderBy(
                'subject_name'
            )->get();


        $subjectAverages = [];


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA TỪNG MÔN
        |--------------------------------------------------------------------------
        */

        foreach (
            $subjects
            as $subject
        ) {

            $score =
                Score::where(
                    'student_id',
                    $student->id
                )
                    ->where(
                        'subject_id',
                        $subject->id
                    )
                    ->where(
                        'school_year_id',
                        $classAssignment->school_year_id
                    )
                    ->first();


            if (!$score) {

                return back()
                    ->with(
                        'error',
                        'Học sinh chưa có đủ điểm của tất cả các môn.'
                    );
            }


            $average =
                $this->calculateCompleteAverage(
                    $score
                );


            if ($average === null) {

                return back()
                    ->with(
                        'error',
                        'Học sinh chưa đủ 4 loại điểm của môn '
                        . ($subject->subject_name ?? '')
                        . '.'
                    );
            }


            $subjectAverages[] =
                $average;
        }


        /*
        |--------------------------------------------------------------------------
        | TÍNH TB CHUNG
        |--------------------------------------------------------------------------
        */

        if (
            count($subjectAverages) === 0
        ) {

            return back()
                ->with(
                    'error',
                    'Chưa có dữ liệu điểm để tính kết quả.'
                );
        }


        $overallAverage =
            round(
                array_sum(
                    $subjectAverages
                )
                /
                count(
                    $subjectAverages
                ),
                2
            );


        /*
        |--------------------------------------------------------------------------
        | XẾP LOẠI
        |--------------------------------------------------------------------------
        */

        $classification =
            $this->classifyStudent(
                $overallAverage,
                $request->conduct,
                $subjectAverages
            );


        /*
        |--------------------------------------------------------------------------
        | LƯU
        |--------------------------------------------------------------------------
        */

        StudentAcademicResult::updateOrCreate(

            [
                'student_id' =>
                    $student->id,

                'school_year_id' =>
                    $classAssignment->school_year_id,
            ],

            [
                'class_id' =>
                    $classAssignment->class_id,

                'overall_average' =>
                    $overallAverage,

                'conduct' =>
                    $request->conduct,

                'classification' =>
                    $classification,
            ]
        );


        return back()
            ->with(
                'success',
                'Đã lưu hạnh kiểm và kết quả học tập của học sinh.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TÍNH ĐIỂM TRUNG BÌNH MÔN
    |--------------------------------------------------------------------------
    */

    private function calculateCompleteAverage(
        Score $score
    ): ?float {

        $values = [

            $score->oral_score,

            $score->fifteen_minute_score,

            $score->midterm_score,

            $score->final_score,
        ];


        foreach (
            $values
            as $value
        ) {

            if (
                $value === null
                || $value === ''
            ) {

                return null;
            }
        }


        return round(

            (
                (float) $score->oral_score
                +
                (float) $score->fifteen_minute_score
                +
                (float) $score->midterm_score
                +
                (float) $score->final_score
            )
            / 4,

            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | XẾP LOẠI HỌC SINH
    |--------------------------------------------------------------------------
    |
    | Đây là logic tạm thời của hệ thống.
    | Phần tiêu chí chính thức theo quy định đánh giá
    | học sinh tiểu học sẽ được hoàn thiện riêng.
    |
    */

    private function classifyStudent(
        float $overallAverage,
        string $conduct,
        array $subjectAverages
    ): string {

        /*
        |--------------------------------------------------------------------------
        | HOÀN THÀNH XUẤT SẮC
        |--------------------------------------------------------------------------
        */

        $allExcellent =
            collect(
                $subjectAverages
            )->every(
                function ($value) {

                    return $value >= 9;
                }
            );


        if (
            $conduct === 'Tốt'
            && $overallAverage >= 9
            && $allExcellent
        ) {

            return 'Hoàn thành xuất sắc';
        }


        /*
        |--------------------------------------------------------------------------
        | HOÀN THÀNH TỐT
        |--------------------------------------------------------------------------
        */

        $allGood =
            collect(
                $subjectAverages
            )->every(
                function ($value) {

                    return $value >= 8;
                }
            );


        if (
            $conduct === 'Tốt'
            && $overallAverage >= 8
            && $allGood
        ) {

            return 'Hoàn thành tốt';
        }


        /*
        |--------------------------------------------------------------------------
        | HOÀN THÀNH
        |--------------------------------------------------------------------------
        */

        $allPassed =
            collect(
                $subjectAverages
            )->every(
                function ($value) {

                    return $value >= 5;
                }
            );


        if (
            $allPassed
            && in_array(
                $conduct,
                [
                    'Tốt',
                    'Đạt',
                ],
                true
            )
        ) {

            return 'Hoàn thành';
        }


        /*
        |--------------------------------------------------------------------------
        | CHƯA HOÀN THÀNH
        |--------------------------------------------------------------------------
        */

        return 'Chưa hoàn thành';
    }


    /*
    |--------------------------------------------------------------------------
    | SO SÁNH ĐIỂM CŨ / MỚI
    |--------------------------------------------------------------------------
    */

    private function sameScoreValue(
        $oldValue,
        $newValue
    ): bool {

        if (
            ($oldValue === null || $oldValue === '')
            &&
            ($newValue === null || $newValue === '')
        ) {

            return true;
        }


        if (
            $oldValue === null
            || $newValue === null
            || $oldValue === ''
            || $newValue === ''
        ) {

            return false;
        }


        return (float) $oldValue
            ===
            (float) $newValue;
    }


    /*
    |--------------------------------------------------------------------------
    | TÊN CỘT ĐIỂM
    |--------------------------------------------------------------------------
    */

    private function scoreFieldLabel(
        string $field
    ): string {

        return match ($field) {

            'oral_score' =>
                'Điểm miệng',

            'fifteen_minute_score' =>
                'Điểm 15 phút',

            'midterm_score' =>
                'Điểm giữa kỳ',

            'final_score' =>
                'Điểm cuối kỳ',

            default =>
                'Điểm',
        };
    }
}