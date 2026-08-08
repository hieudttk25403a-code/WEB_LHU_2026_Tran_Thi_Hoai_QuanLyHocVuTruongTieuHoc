<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request)
{
    $query = Score::with([
        'student',
        'subject',
        'schoolYear'
    ]);

    if ($request->filled('keyword')) {

        $keyword = $request->keyword;

        $query->whereHas('student', function ($q) use ($keyword) {
            $q->where('full_name', 'like', '%' . $keyword . '%')
              ->orWhere('student_code', 'like', '%' . $keyword . '%');
        });

    }

    if ($request->filled('subject_id')) {
        $query->where('subject_id', $request->subject_id);
    }

    if ($request->filled('school_year_id')) {
        $query->where('school_year_id', $request->school_year_id);
    }

    $scores = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $subjects = Subject::orderBy('subject_name')->get();

    $schoolYears = SchoolYear::orderByDesc('name')->get();

    return view('scores.index', compact(
        'scores',
        'subjects',
        'schoolYears'
    ));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
public function create()
{
    $students = Student::orderBy('full_name')->get();

    $subjects = Subject::orderBy('subject_name')->get();

    $schoolYears = SchoolYear::orderByDesc('name')->get();

    return view('scores.create', compact(
        'students',
        'subjects',
        'schoolYears'
    ));
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'subject_id' => 'required|exists:subjects,id',
        'school_year_id' => 'required|exists:school_years,id',

        'oral_score' => 'nullable|numeric|min:0|max:10',
        'fifteen_minute_score' => 'nullable|numeric|min:0|max:10',
        'midterm_score' => 'nullable|numeric|min:0|max:10',
        'final_score' => 'nullable|numeric|min:0|max:10',
    ], [
        'student_id.required' => 'Vui lòng chọn học sinh.',
        'subject_id.required' => 'Vui lòng chọn môn học.',
        'school_year_id.required' => 'Vui lòng chọn năm học.',

        'oral_score.numeric' => 'Điểm miệng phải là số.',
        'fifteen_minute_score.numeric' => 'Điểm 15 phút phải là số.',
        'midterm_score.numeric' => 'Điểm giữa kỳ phải là số.',
        'final_score.numeric' => 'Điểm cuối kỳ phải là số.',

        'oral_score.max' => 'Điểm không được lớn hơn 10.',
        'fifteen_minute_score.max' => 'Điểm không được lớn hơn 10.',
        'midterm_score.max' => 'Điểm không được lớn hơn 10.',
        'final_score.max' => 'Điểm không được lớn hơn 10.',
    ]);

    $oral = $request->oral_score;
    $fifteen = $request->fifteen_minute_score;
    $midterm = $request->midterm_score;
    $final = $request->final_score;

    $regularScores = collect([
        $oral,
        $fifteen,
    ])->filter(function ($score) {
        return $score !== null && $score !== '';
    });

    $averageScore = null;

    if (
        $regularScores->count() > 0 &&
        $midterm !== null &&
        $midterm !== '' &&
        $final !== null &&
        $final !== ''
    ) {
        $regularAverage = $regularScores->avg();

        $averageScore = (
            $regularAverage +
            ($midterm * 2) +
            ($final * 3)
        ) / 6;

        $averageScore = round($averageScore, 2);
    }

    $classification = null;

    if ($averageScore !== null) {

        if ($averageScore >= 8) {
            $classification = 'Tốt';
        } elseif ($averageScore >= 6.5) {
            $classification = 'Khá';
        } elseif ($averageScore >= 5) {
            $classification = 'Đạt';
        } else {
            $classification = 'Chưa đạt';
        }
    }

    Score::create([
        'student_id' => $request->student_id,
        'subject_id' => $request->subject_id,
        'school_year_id' => $request->school_year_id,

        'oral_score' => $oral,
        'fifteen_minute_score' => $fifteen,
        'midterm_score' => $midterm,
        'final_score' => $final,

        'average_score' => $averageScore,
        'classification' => $classification,
    ]);

    return redirect()
        ->route('scores.index')
        ->with('success', 'Nhập điểm thành công!');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function show(Score $score)
{
    $score->load([
        'student',
        'subject',
        'schoolYear'
    ]);

    return view('scores.show', compact('score'));
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function edit(Score $score)
{
    $students = Student::orderBy('full_name')->get();

    $subjects = Subject::orderBy('subject_name')->get();

    $schoolYears = SchoolYear::orderByDesc('name')->get();

    return view('scores.edit', compact(
        'score',
        'students',
        'subjects',
        'schoolYears'
    ));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, Score $score)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'subject_id' => 'required|exists:subjects,id',
        'school_year_id' => 'required|exists:school_years,id',

        'oral_score' => 'nullable|numeric|min:0|max:10',
        'fifteen_minute_score' => 'nullable|numeric|min:0|max:10',
        'midterm_score' => 'nullable|numeric|min:0|max:10',
        'final_score' => 'nullable|numeric|min:0|max:10',
    ], [
        'student_id.required' => 'Vui lòng chọn học sinh.',
        'subject_id.required' => 'Vui lòng chọn môn học.',
        'school_year_id.required' => 'Vui lòng chọn năm học.',

        'oral_score.numeric' => 'Điểm miệng phải là số.',
        'fifteen_minute_score.numeric' => 'Điểm 15 phút phải là số.',
        'midterm_score.numeric' => 'Điểm giữa kỳ phải là số.',
        'final_score.numeric' => 'Điểm cuối kỳ phải là số.',
    ]);

    $oral = $request->oral_score;
    $fifteen = $request->fifteen_minute_score;
    $midterm = $request->midterm_score;
    $final = $request->final_score;

    $regularScores = collect([
        $oral,
        $fifteen,
    ])->filter(function ($score) {
        return $score !== null && $score !== '';
    });

    $averageScore = null;

    if (
        $regularScores->count() > 0 &&
        $midterm !== null &&
        $midterm !== '' &&
        $final !== null &&
        $final !== ''
    ) {
        $regularAverage = $regularScores->avg();

        $averageScore = (
            $regularAverage +
            ($midterm * 2) +
            ($final * 3)
        ) / 6;

        $averageScore = round($averageScore, 2);
    }

    $classification = null;

    if ($averageScore !== null) {

        if ($averageScore >= 8) {
            $classification = 'Tốt';
        } elseif ($averageScore >= 6.5) {
            $classification = 'Khá';
        } elseif ($averageScore >= 5) {
            $classification = 'Đạt';
        } else {
            $classification = 'Chưa đạt';
        }
    }

    $score->update([
        'student_id' => $request->student_id,
        'subject_id' => $request->subject_id,
        'school_year_id' => $request->school_year_id,

        'oral_score' => $oral,
        'fifteen_minute_score' => $fifteen,
        'midterm_score' => $midterm,
        'final_score' => $final,

        'average_score' => $averageScore,
        'classification' => $classification,
    ]);

    return redirect()
        ->route('scores.index')
        ->with('success', 'Cập nhật điểm thành công!');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function destroy(Score $score)
{
    $score->delete();

    return redirect()
        ->route('scores.index')
        ->with('success', 'Xóa bảng điểm thành công!');
}
}
