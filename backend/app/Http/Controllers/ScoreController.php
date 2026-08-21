<?php

namespace App\Http\Controllers;

use App\Models\ClassAssignment;
use App\Models\Score;
use App\Models\ScoreEditHistory;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH ĐIỂM
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | GIÁO VIÊN
        |--------------------------------------------------------------------------
        */

        if ($user && $user->isTeacher()) {
            return $this->teacherIndex($request);
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $query = Score::with([
            'student.schoolClass',
            'subject',
            'schoolYear',
            'teacher',
            'editHistories',
        ]);

        if ($request->filled('keyword')) {

            $keyword = trim(
                $request->keyword
            );

            $query->whereHas(
                'student',
                function ($q) use ($keyword) {

                    $q->where(
                        function ($studentQuery) use ($keyword) {

                            $studentQuery
                                ->where(
                                    'full_name',
                                    'like',
                                    '%' . $keyword . '%'
                                )
                                ->orWhere(
                                    'student_code',
                                    'like',
                                    '%' . $keyword . '%'
                                );
                        }
                    );
                }
            );
        }

        if ($request->filled('subject_id')) {

            $query->where(
                'subject_id',
                $request->subject_id
            );
        }

        if ($request->filled('school_year_id')) {

            $query->where(
                'school_year_id',
                $request->school_year_id
            );
        }

        $scores = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $subjects = Subject::orderBy(
            'subject_name'
        )->get();

        $schoolYears = SchoolYear::orderByDesc(
            'id'
        )->get();

        return view(
            'scores.index',
            compact(
                'scores',
                'subjects',
                'schoolYears'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GIAO DIỆN ĐIỂM CỦA GIÁO VIÊN
    |--------------------------------------------------------------------------
    */

    private function teacherIndex(Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {

            abort(
                403,
                'Tài khoản giáo viên chưa được liên kết với giáo viên.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | XÁC ĐỊNH NĂM HỌC
        |--------------------------------------------------------------------------
        */

        $schoolYearId =
            $request->school_year_id;

        if (!$schoolYearId) {

            $activeYear =
                SchoolYear::where(
                    'is_active',
                    1
                )->first();

            if ($activeYear) {

                $schoolYearId =
                    $activeYear->id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NẾU CHƯA CÓ NĂM HỌC
        |--------------------------------------------------------------------------
        */

        if (!$schoolYearId) {

            return view(
                'teachers.scores.index',
                [
                    'teacher' =>
                        $teacher,

                    'schoolYear' =>
                        null,

                    'schoolYears' =>
                        SchoolYear::orderByDesc(
                            'id'
                        )->get(),

                    'classes' =>
                        collect(),

                    'students' =>
                        collect(),

                    'subjects' =>
                        Subject::orderBy(
                            'subject_name'
                        )->get(),

                    'scoreMap' =>
                        collect(),

                    'isHomeroom' =>
                        false,
                ]
            );
        }

        $schoolYear =
            SchoolYear::findOrFail(
                $schoolYearId
            );

        /*
        |--------------------------------------------------------------------------
        | PHÂN CÔNG CHỦ NHIỆM
        |--------------------------------------------------------------------------
        */

        $homeroomAssignments =
            ClassAssignment::with(
                'schoolClass'
            )
                ->where(
                    'teacher_id',
                    $teacher->id
                )
                ->where(
                    'school_year_id',
                    $schoolYearId
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | PHÂN CÔNG BỘ MÔN
        |--------------------------------------------------------------------------
        */

        $subjectAssignments =
            TeacherSubjectAssignment::with([
                'subject',
                'schoolClass',
            ])
                ->where(
                    'teacher_id',
                    $teacher->id
                )
                ->where(
                    'school_year_id',
                    $schoolYearId
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | LẤY LỚP ĐƯỢC QUYỀN XEM
        |--------------------------------------------------------------------------
        */

        $classIds = collect();

        $classIds =
            $classIds->merge(
                $homeroomAssignments->pluck(
                    'class_id'
                )
            );

        $classIds =
            $classIds->merge(
                $subjectAssignments->pluck(
                    'class_id'
                )
            );

        $classIds =
            $classIds
                ->unique()
                ->values();

        /*
        |--------------------------------------------------------------------------
        | CHƯA ĐƯỢC PHÂN CÔNG
        |--------------------------------------------------------------------------
        */

        if ($classIds->isEmpty()) {

            return view(
                'teachers.scores.index',
                [
                    'teacher' =>
                        $teacher,

                    'schoolYear' =>
                        $schoolYear,

                    'schoolYears' =>
                        SchoolYear::orderByDesc(
                            'id'
                        )->get(),

                    'classes' =>
                        collect(),

                    'students' =>
                        collect(),

                    'subjects' =>
                        collect(),

                    'scoreMap' =>
                        collect(),

                    'isHomeroom' =>
                        false,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LỚP
        |--------------------------------------------------------------------------
        */

        $classes =
            \App\Models\SchoolClass::whereIn(
                'id',
                $classIds
            )
                ->orderBy('grade')
                ->orderBy('class_name')
                ->get();

        /*
        |--------------------------------------------------------------------------
        | LỚP ĐANG CHỌN
        |--------------------------------------------------------------------------
        */

        $selectedClassId =
            $request->class_id;

        if (
            !$selectedClassId
            ||
            !$classIds->contains(
                (int) $selectedClassId
            )
        ) {

            $selectedClassId =
                $classIds->first();
        }

        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA GVCN CỦA LỚP
        |--------------------------------------------------------------------------
        */

        $isHomeroom =
            $homeroomAssignments->contains(
                'class_id',
                (int) $selectedClassId
            );

        /*
        |--------------------------------------------------------------------------
        | MÔN ĐƯỢC PHÉP HIỂN THỊ
        |--------------------------------------------------------------------------
        */

        if ($isHomeroom) {

            $subjects =
                Subject::orderBy(
                    'subject_name'
                )->get();

        } else {

            $subjectIds =
                $subjectAssignments
                    ->where(
                        'class_id',
                        $selectedClassId
                    )
                    ->pluck(
                        'subject_id'
                    )
                    ->unique();

            $subjects =
                Subject::whereIn(
                    'id',
                    $subjectIds
                )
                    ->orderBy(
                        'subject_name'
                    )
                    ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | HỌC SINH
        |--------------------------------------------------------------------------
        */

        $historyStudentIds =
            \App\Models\StudentClassHistory::where(
                'class_id',
                $selectedClassId
            )
                ->where(
                    'school_year_id',
                    $schoolYearId
                )
                ->pluck(
                    'student_id'
                );

        $students =
            Student::with(
                'schoolClass'
            )
                ->where(
                    function ($q) use (
                        $historyStudentIds,
                        $selectedClassId
                    ) {

                        $q->whereIn(
                            'id',
                            $historyStudentIds
                        )
                        ->orWhere(
                            'class_id',
                            $selectedClassId
                        );
                    }
                )
                ->orderBy(
                    'full_name'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | TÌM KIẾM
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword =
                trim(
                    $request->keyword
                );

            $students =
                $students
                    ->filter(
                        function ($student) use (
                            $keyword
                        ) {

                            return
                                stripos(
                                    $student->full_name,
                                    $keyword
                                ) !== false

                                ||

                                stripos(
                                    $student->student_code,
                                    $keyword
                                ) !== false;
                        }
                    )
                    ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | LẤY ĐIỂM
        |--------------------------------------------------------------------------
        */

        $studentIds =
            $students->pluck('id');

        $subjectIds =
            $subjects->pluck('id');

        $scores =
            Score::with([
                'subject',
                'editHistories',
            ])
                ->whereIn(
                    'student_id',
                    $studentIds
                )
                ->whereIn(
                    'subject_id',
                    $subjectIds
                )
                ->where(
                    'school_year_id',
                    $schoolYearId
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | MAP ĐIỂM
        |--------------------------------------------------------------------------
        */

        $scoreMap =
            $scores->keyBy(
                function ($score) {

                    return
                        $score->student_id
                        . '_'
                        . $score->subject_id;
                }
            );

        /*
        |--------------------------------------------------------------------------
        | TRẢ VỀ GIAO DIỆN GIÁO VIÊN
        |--------------------------------------------------------------------------
        */

        return view(
            'teachers.scores.index',
            [
                'teacher' =>
                    $teacher,

                'schoolYear' =>
                    $schoolYear,

                'schoolYears' =>
                    SchoolYear::orderByDesc(
                        'id'
                    )->get(),

                'classes' =>
                    $classes,

                'students' =>
                    $students,

                'subjects' =>
                    $subjects,

                'scoreMap' =>
                    $scoreMap,

                'isHomeroom' =>
                    $isHomeroom,

                'selectedClassId' =>
                    $selectedClassId,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM THÊM ĐIỂM
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = Auth::user();

        if ($user && $user->isTeacher()) {

            $teacher =
                $user->teacher;

            if (!$teacher) {

                abort(
                    403,
                    'Tài khoản giáo viên chưa được liên kết với giáo viên.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | GVCN + GV BỘ MÔN
            |--------------------------------------------------------------------------
            */

            $homeroomAssignments =
                ClassAssignment::with([
                    'schoolClass',
                    'schoolYear',
                ])
                    ->where(
                        'teacher_id',
                        $teacher->id
                    )
                    ->get();

            $subjectAssignments =
                TeacherSubjectAssignment::with([
                    'subject',
                    'schoolClass',
                    'schoolYear',
                ])
                    ->where(
                        'teacher_id',
                        $teacher->id
                    )
                    ->get();

            $classIds =
                $homeroomAssignments
                    ->pluck('class_id')
                    ->merge(
                        $subjectAssignments->pluck(
                            'class_id'
                        )
                    )
                    ->unique();

 $students = Student::whereIn(
    'class_id',
    $classIds
)
    ->with([
        'academicResults' => function ($query) use ($schoolYearId) {
            $query->where(
                'school_year_id',
                $schoolYearId
            );
        }
    ])
    ->orderBy('full_name')
    ->get();

            /*
            | GVCN được thấy toàn bộ môn.
            | GV bộ môn chỉ thấy môn được phân công.
            */

            if (
                $homeroomAssignments->isNotEmpty()
            ) {

                $subjects =
                    Subject::orderBy(
                        'subject_name'
                    )->get();

            } else {

                $subjects =
                    Subject::whereIn(
                        'id',
                        $subjectAssignments->pluck(
                            'subject_id'
                        )
                    )
                        ->orderBy(
                            'subject_name'
                        )
                        ->get();
            }

            $schoolYears =
                SchoolYear::whereIn(
                    'id',
                    $homeroomAssignments
                        ->pluck('school_year_id')
                        ->merge(
                            $subjectAssignments->pluck(
                                'school_year_id'
                            )
                        )
                        ->unique()
                )
                    ->orderByDesc(
                        'id'
                    )
                    ->get();

            return view(
                'scores.create',
                [
                    'students' =>
                        $students,

                    'subjects' =>
                        $subjects,

                    'schoolYears' =>
                        $schoolYears,

                    'teachers' =>
                        collect([
                            $teacher
                        ]),

                    'assignments' =>
                        $subjectAssignments,

                    'homeroomAssignments' =>
                        $homeroomAssignments,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $students =
            Student::orderBy(
                'full_name'
            )->get();

        $subjects =
            Subject::orderBy(
                'subject_name'
            )->get();

        $schoolYears =
            SchoolYear::orderByDesc(
                'id'
            )->get();

        $teachers =
            Teacher::orderBy(
                'full_name'
            )->get();

        return view(
            'scores.create',
            compact(
                'students',
                'subjects',
                'schoolYears',
                'teachers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LƯU ĐIỂM
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ) {

        $user =
            Auth::user();

        $validated =
            $request->validate([

                'student_id' =>
                    'required|exists:students,id',

                'subject_id' =>
                    'required|exists:subjects,id',

                'school_year_id' =>
                    'required|exists:school_years,id',

                'oral_score' =>
                    'nullable|numeric|min:0|max:10',

                'fifteen_minute_score' =>
                    'nullable|numeric|min:0|max:10',

                'midterm_score' =>
                    'nullable|numeric|min:0|max:10',

                'final_score' =>
                    'nullable|numeric|min:0|max:10',

                'note' =>
                    'nullable|string|max:1000',
            ]);

        /*
        |--------------------------------------------------------------------------
        | GIÁO VIÊN
        |--------------------------------------------------------------------------
        */

        if (
            $user
            && $user->isTeacher()
        ) {

            $teacher =
                $user->teacher;

            if (!$teacher) {

                abort(
                    403,
                    'Tài khoản giáo viên chưa được liên kết.'
                );
            }

            $student =
                Student::findOrFail(
                    $validated['student_id']
                );

            /*
            | Có phải GVCN lớp này không?
            */

            $isHomeroom =
                ClassAssignment::where(
                    'teacher_id',
                    $teacher->id
                )
                    ->where(
                        'class_id',
                        $student->class_id
                    )
                    ->where(
                        'school_year_id',
                        $validated[
                            'school_year_id'
                        ]
                    )
                    ->exists();

            /*
            | Có được phân công môn này không?
            */

            $isSubjectTeacher =
                TeacherSubjectAssignment::where(
                    'teacher_id',
                    $teacher->id
                )
                    ->where(
                        'subject_id',
                        $validated[
                            'subject_id'
                        ]
                    )
                    ->where(
                        'class_id',
                        $student->class_id
                    )
                    ->where(
                        'school_year_id',
                        $validated[
                            'school_year_id'
                        ]
                    )
                    ->exists();

            if (
                !$isHomeroom
                && !$isSubjectTeacher
            ) {

                abort(
                    403,
                    'Bạn không được phân công lớp/môn này.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KHÔNG CHO TRÙNG
        |--------------------------------------------------------------------------
        */

        $exists =
            Score::where(
                'student_id',
                $validated['student_id']
            )
                ->where(
                    'subject_id',
                    $validated['subject_id']
                )
                ->where(
                    'school_year_id',
                    $validated[
                        'school_year_id'
                    ]
                )
                ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'Học sinh này đã có bảng điểm môn này trong năm học đã chọn.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | TẠO
        |--------------------------------------------------------------------------
        */

        $score =
            new Score();

        $score->student_id =
            $validated['student_id'];

        $score->subject_id =
            $validated['subject_id'];

        $score->school_year_id =
            $validated['school_year_id'];

        /*
        | Không lưu teacher_id vì bảng scores
        | của project hiện tại không có cột này.
        */

        $score->oral_score =
            $validated['oral_score']
            ?? null;

        $score->fifteen_minute_score =
            $validated[
                'fifteen_minute_score'
            ] ?? null;

        $score->midterm_score =
            $validated['midterm_score']
            ?? null;

        $score->final_score =
            $validated['final_score']
            ?? null;

        $score->note =
            $validated['note']
            ?? null;

        $score->average_score =
            $score->calculateAverage();

        $score->save();

        if (
            $user
            && $user->isTeacher()
        ) {

            return redirect()
                ->route(
                    'teacher.scores.index',
                    [
                        'school_year_id' =>
                            $validated[
                                'school_year_id'
                            ],

                        'class_id' =>
                            $score->student->class_id,
                    ]
                )
                ->with(
                    'success',
                    'Nhập điểm thành công!'
                );
        }

        return redirect()
            ->route('scores.index')
            ->with(
                'success',
                'Thêm điểm thành công!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | XEM CHI TIẾT
    |--------------------------------------------------------------------------
    */

    public function show(
        Score $score
    ) {

        $user =
            Auth::user();

        if (
            $user
            && $user->isTeacher()
        ) {

            $this->ensureTeacherCanAccessScore(
                $score
            );
        }

        $score->load([
            'student.schoolClass',
            'subject',
            'schoolYear',
            'editHistories.user',
        ]);

        return view(
            'scores.show',
            compact('score')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM SỬA
    |--------------------------------------------------------------------------
    */

    public function edit(
        Score $score
    ) {

        $user =
            Auth::user();

        /*
        |--------------------------------------------------------------------------
        | GIÁO VIÊN
        |--------------------------------------------------------------------------
        */

        if (
            $user
            && $user->isTeacher()
        ) {

            $this->ensureTeacherCanAccessScore(
                $score
            );

            $this->ensureTeacherCanEditScore(
                $score
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        |
        | Admin chỉ được sửa sau khi giáo viên
        | đã sử dụng đủ 3 lần chỉnh sửa.
        |
        */

        if (
            $user
            && $user->isAdmin()
        ) {

            $editCount =
                ScoreEditHistory::where(
                    'score_id',
                    $score->id
                )->count();

            if (
                $editCount < 3
            ) {

                return redirect()
                    ->route(
                        'scores.index'
                    )
                    ->with(
                        'error',
                        'Bạn chưa có quyền sửa điểm. Giáo viên chưa sử dụng hết 3 lần chỉnh sửa.'
                    );
            }
        }

        $students =
            Student::orderBy(
                'full_name'
            )->get();

        $subjects =
            Subject::orderBy(
                'subject_name'
            )->get();

        $schoolYears =
            SchoolYear::orderByDesc(
                'id'
            )->get();

        $teachers =
            Teacher::orderBy(
                'full_name'
            )->get();

        $score->load(
            'editHistories'
        );

        return view(
            'scores.edit',
            compact(
                'score',
                'students',
                'subjects',
                'schoolYears',
                'teachers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CẬP NHẬT
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Score $score
    ) {

        $user =
            Auth::user();

        $validated =
            $request->validate([

                'student_id' =>
                    'required|exists:students,id',

                'subject_id' =>
                    'required|exists:subjects,id',

                'school_year_id' =>
                    'required|exists:school_years,id',

                'oral_score' =>
                    'nullable|numeric|min:0|max:10',

                'fifteen_minute_score' =>
                    'nullable|numeric|min:0|max:10',

                'midterm_score' =>
                    'nullable|numeric|min:0|max:10',

                'final_score' =>
                    'nullable|numeric|min:0|max:10',

                'note' =>
                    'nullable|string|max:1000',
            ]);

        /*
        |--------------------------------------------------------------------------
        | GIÁO VIÊN
        |--------------------------------------------------------------------------
        */

        if (
            $user
            && $user->isTeacher()
        ) {

            $this->ensureTeacherCanAccessScore(
                $score
            );

            $result =
                $this->updateAsTeacher(
                    $request,
                    $score,
                    $validated
                );

            if (
                $result !== null
            ) {

                return $result;
            }

            return redirect()
                ->route(
                    'teacher.scores.index',
                    [
                        'school_year_id' =>
                            $score->school_year_id,

                        'class_id' =>
                            $score->student->class_id,
                    ]
                )
                ->with(
                    'success',
                    'Cập nhật điểm thành công!'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $user
            && $user->isAdmin()
        ) {

            $editCount =
                ScoreEditHistory::where(
                    'score_id',
                    $score->id
                )->count();

            /*
            | Không đủ 3 lần thì không được sửa.
            */

            if (
                $editCount < 3
            ) {

                return redirect()
                    ->route(
                        'scores.index'
                    )
                    ->with(
                        'error',
                        'Bạn chưa có quyền sửa điểm. Giáo viên chưa sử dụng hết 3 lần chỉnh sửa.'
                    );
            }

            $result =
                $this->updateAsAdmin(
                    $score,
                    $validated
                );

            if (
                $result !== null
            ) {

                return $result;
            }

            return redirect()
                ->route(
                    'scores.index'
                )
                ->with(
                    'success',
                    'Quản trị viên đã cập nhật điểm thành công!'
                );
        }

        abort(
            403,
            'Bạn không có quyền chỉnh sửa điểm.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GIÁO VIÊN CẬP NHẬT
    |--------------------------------------------------------------------------
    |
    | QUAN TRỌNG:
    |
    | Một lần lưu có thay đổi bất kỳ cột nào
    | trong 4 cột điểm = 1 lần sửa.
    |
    | Không tính riêng từng cột.
    |
    */

    private function updateAsTeacher(
        Request $request,
        Score $score,
        array $validated
    ) {

        $scoreFields = [
            'oral_score',
            'fifteen_minute_score',
            'midterm_score',
            'final_score',
        ];

        /*
        |--------------------------------------------------------------------------
        | ĐẾM SỐ LẦN ĐÃ SỬA
        |--------------------------------------------------------------------------
        */

        $editCount =
            ScoreEditHistory::where(
                'score_id',
                $score->id
            )->count();

        /*
        |--------------------------------------------------------------------------
        | ĐÃ ĐỦ 3 LẦN
        |--------------------------------------------------------------------------
        */

        if (
            $editCount >= 3
        ) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Bạn đã sử dụng đủ 3 lần chỉnh sửa điểm. Vui lòng liên hệ Quản trị viên.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | TÌM CÁC CỘT BỊ THAY ĐỔI
        |--------------------------------------------------------------------------
        */

        $changedFields = [];

        foreach (
            $scoreFields
            as $field
        ) {

            $oldValue =
                $score->{$field};

            $newValue =
                $validated[$field]
                ?? null;

            if (
                $this->sameScoreValue(
                    $oldValue,
                    $newValue
                )
            ) {

                continue;
            }

            $changedFields[$field] = [
                'old' =>
                    $oldValue,

                'new' =>
                    $newValue,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | KHÔNG THAY ĐỔI ĐIỂM
        |--------------------------------------------------------------------------
        */

        if (
            empty($changedFields)
        ) {

            $score->note =
                $validated['note']
                ?? null;

            $score->average_score =
                $score->calculateAverage();

            $score->save();

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | GHI 1 LỊCH SỬ CHO 1 LẦN SỬA
        |--------------------------------------------------------------------------
        |
        | Dù thay đổi 1 hay 4 cột cũng chỉ tạo 1 history.
        |
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
                'score_edit',

            'old_value' =>
                null,

            'new_value' =>
                null,

            'note' =>
                'Lần sửa '
                . ($editCount + 1)
                . '/3. '
                . implode(
                    '; ',
                    $changeDescription
                ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | CẬP NHẬT ĐIỂM
        |--------------------------------------------------------------------------
        */

        foreach (
            $changedFields
            as $field => $change
        ) {

            $score->{$field} =
                $change['new'];
        }

        /*
        |--------------------------------------------------------------------------
        | GHI CHÚ
        |--------------------------------------------------------------------------
        */

        $score->note =
            $validated['note']
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | TÍNH TRUNG BÌNH
        |--------------------------------------------------------------------------
        */

        $score->average_score =
            $score->calculateAverage();

        /*
        |--------------------------------------------------------------------------
        | LƯU
        |--------------------------------------------------------------------------
        */

        $score->save();

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN CẬP NHẬT
    |--------------------------------------------------------------------------
    */

    private function updateAsAdmin(
        Score $score,
        array $validated
    ) {

        $editCount =
            ScoreEditHistory::where(
                'score_id',
                $score->id
            )->count();

        /*
        |--------------------------------------------------------------------------
        | CHỈ ĐƯỢC SỬA KHI ĐÃ ĐỦ 3 LẦN
        |--------------------------------------------------------------------------
        */

        if (
            $editCount < 3
        ) {

            return redirect()
                ->route(
                    'scores.index'
                )
                ->with(
                    'error',
                    'Bạn chưa có quyền sửa điểm. Giáo viên chưa sử dụng hết 3 lần chỉnh sửa.'
                );
        }

        $scoreFields = [
            'oral_score',
            'fifteen_minute_score',
            'midterm_score',
            'final_score',
        ];

        $hasChanged =
            false;

        foreach (
            $scoreFields
            as $field
        ) {

            $oldValue =
                $score->{$field};

            $newValue =
                $validated[$field]
                ?? null;

            if (
                $this->sameScoreValue(
                    $oldValue,
                    $newValue
                )
            ) {

                continue;
            }

            $score->{$field} =
                $newValue;

            $hasChanged =
                true;
        }

        /*
        |--------------------------------------------------------------------------
        | KHÔNG CÓ THAY ĐỔI
        |--------------------------------------------------------------------------
        */

        if (!$hasChanged) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Quản trị viên chưa thay đổi cột điểm nào.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | GHI CHÚ
        |--------------------------------------------------------------------------
        */

        $score->note =
            $validated['note']
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | TÍNH TRUNG BÌNH
        |--------------------------------------------------------------------------
        */

        $score->average_score =
            $score->calculateAverage();

        $score->save();

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | XÓA
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Score $score
    ) {

        $score->delete();

        return redirect()
            ->route(
                'scores.index'
            )
            ->with(
                'success',
                'Xóa điểm thành công!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA GIÁO VIÊN ĐƯỢC XEM ĐIỂM
    |--------------------------------------------------------------------------
    */

    private function ensureTeacherCanAccessScore(
        Score $score
    ) {

        $teacher =
            Auth::user()->teacher;

        if (!$teacher) {

            abort(
                403,
                'Tài khoản giáo viên chưa được liên kết với giáo viên.'
            );
        }

        $student =
            $score->student;

        if (!$student) {

            abort(
                404,
                'Không tìm thấy học sinh.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GVCN
        |--------------------------------------------------------------------------
        */

        $isHomeroom =
            ClassAssignment::where(
                'teacher_id',
                $teacher->id
            )
                ->where(
                    'class_id',
                    $student->class_id
                )
                ->where(
                    'school_year_id',
                    $score->school_year_id
                )
                ->exists();

        if ($isHomeroom) {

            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | GIÁO VIÊN BỘ MÔN
        |--------------------------------------------------------------------------
        */

        $isSubjectTeacher =
            TeacherSubjectAssignment::where(
                'teacher_id',
                $teacher->id
            )
                ->where(
                    'subject_id',
                    $score->subject_id
                )
                ->where(
                    'class_id',
                    $student->class_id
                )
                ->where(
                    'school_year_id',
                    $score->school_year_id
                )
                ->exists();

        if ($isSubjectTeacher) {

            return true;
        }

        abort(
            403,
            'Bạn không được truy cập điểm của học sinh này.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA GIÁO VIÊN CÒN QUYỀN SỬA
    |--------------------------------------------------------------------------
    */

    private function ensureTeacherCanEditScore(
        Score $score
    ) {

        $editCount =
            ScoreEditHistory::where(
                'score_id',
                $score->id
            )->count();

        if (
            $editCount >= 3
        ) {

            abort(
                403,
                'Bạn đã sử dụng đủ 3 lần chỉnh sửa điểm. Vui lòng liên hệ Quản trị viên.'
            );
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | SO SÁNH ĐIỂM
    |--------------------------------------------------------------------------
    */

    private function sameScoreValue(
        $oldValue,
        $newValue
    ): bool {

        if (
            (
                $oldValue === null
                ||
                $oldValue === ''
            )
            &&
            (
                $newValue === null
                ||
                $newValue === ''
            )
        ) {

            return true;
        }

        if (
            $oldValue === null
            ||
            $newValue === null
            ||
            $oldValue === ''
            ||
            $newValue === ''
        ) {

            return false;
        }

        return abs(
            (float) $oldValue
            -
            (float) $newValue
        ) < 0.0001;
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
public function updateConduct(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | XÁC ĐỊNH NĂM HỌC
    |--------------------------------------------------------------------------
    |
    | Nếu form gửi school_year_id thì dùng giá trị đó.
    | Nếu không gửi thì tự lấy năm học đang hoạt động.
    |
    */

    $schoolYearId = $request->input('school_year_id');

    if (!$schoolYearId) {
        $schoolYearId = \App\Models\SchoolYear::where(
            'is_active',
            1
        )->value('id');
    }

    if (!$schoolYearId) {
        return back()
            ->withInput()
            ->withErrors([
                'school_year_id' =>
                    'Hệ thống chưa có năm học đang hoạt động.'
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GÁN LẠI VÀO REQUEST
    |--------------------------------------------------------------------------
    */

    $request->merge([
        'school_year_id' => $schoolYearId,
    ]);

    /*
    |--------------------------------------------------------------------------
    | VALIDATE
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'student_id' => [
            'required',
            'exists:students,id',
        ],

        'school_year_id' => [
            'required',
            'exists:school_years,id',
        ],

        'conduct' => [
            'required',
            'in:Tốt,Khá,Đạt,Chưa đạt',
        ],
    ]);

    $user = Auth::user();

    if (!$user || !$user->isTeacher()) {
        abort(403);
    }

    $teacher = $user->teacher;

    if (!$teacher) {
        abort(
            403,
            'Tài khoản giáo viên chưa được liên kết với hồ sơ giáo viên.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA HỌC SINH
    |--------------------------------------------------------------------------
    */

    $student = Student::with('schoolClass')
        ->findOrFail($request->student_id);

    if (!$student->schoolClass) {
        abort(
            403,
            'Học sinh chưa được phân lớp.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA GIÁO VIÊN CÓ PHẢI GVCN KHÔNG
    |--------------------------------------------------------------------------
    */

    $isHomeroomTeacher = \App\Models\ClassAssignment::where(
        'teacher_id',
        $teacher->id
    )
        ->where(
            'class_id',
            $student->class_id
        )
        ->where(
            'school_year_id',
            $schoolYearId
        )
        ->whereNull('end_date')
        ->exists();

    if (!$isHomeroomTeacher) {
        abort(
            403,
            'Chỉ giáo viên chủ nhiệm mới được nhập hạnh kiểm.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LƯU HẠNH KIỂM
    |--------------------------------------------------------------------------
    */

    $result = \App\Models\StudentYearResult::firstOrNew([
        'student_id' => $student->id,
        'school_year_id' => $schoolYearId,
    ]);

    $result->conduct = $request->conduct;

    $result->save();

    return back()->with(
        'success',
        'Đã cập nhật hạnh kiểm cho học sinh.'
    );

    $user = Auth::user();

    if (!$user || !$user->isTeacher()) {
        abort(403);
    }

    $teacher = $user->teacher;

    if (!$teacher) {
        abort(
            403,
            'Tài khoản giáo viên chưa được liên kết với hồ sơ giáo viên.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA GIÁO VIÊN CÓ PHẢI GVCN CỦA LỚP KHÔNG
    |--------------------------------------------------------------------------
    */

    $student = Student::with('schoolClass')
        ->findOrFail($request->student_id);

    if (!$student->schoolClass) {
        abort(403, 'Học sinh chưa được phân lớp.');
    }

    $isHomeroomTeacher = \App\Models\ClassAssignment::where(
        'teacher_id',
        $teacher->id
    )
        ->where(
            'class_id',
            $student->class_id
        )
        ->where(
            'school_year_id',
            $request->school_year_id
        )
        ->whereNull('end_date')
        ->exists();

    if (!$isHomeroomTeacher) {
        abort(
            403,
            'Chỉ giáo viên chủ nhiệm mới được nhập hạnh kiểm.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LƯU HẠNH KIỂM
    |--------------------------------------------------------------------------
    */

    $result = \App\Models\StudentYearResult::firstOrNew([
        'student_id' => $student->id,
        'school_year_id' => $request->school_year_id,
    ]);

    $result->conduct = $request->conduct;

    $result->save();

    return back()->with(
        'success',
        'Đã cập nhật hạnh kiểm cho học sinh.'
    );
}
}