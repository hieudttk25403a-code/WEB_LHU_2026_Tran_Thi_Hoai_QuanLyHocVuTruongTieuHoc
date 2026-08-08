<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    $announcements = Announcement::with('creator')
        ->latest()
        ->paginate(10);

    return view('announcements.index', compact('announcements'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
public function create()
{
    return view('announcements.create');
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
        'title' => 'required',
        'content' => 'required',
    ], [
        'title.required' => 'Vui lòng nhập tiêu đề thông báo.',
        'content.required' => 'Vui lòng nhập nội dung thông báo.',
    ]);

    Announcement::create([
        'title' => $request->title,
        'content' => $request->content,
        'created_by' => auth()->id(),
        'is_published' => true,
    ]);

    return redirect()
        ->route('announcements.index')
        ->with('success', 'Thêm thông báo thành công!');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function show(Announcement $announcement)
{
    $announcement->load('creator');

    return view('announcements.show', compact('announcement'));
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function edit(Announcement $announcement)
{
    return view('announcements.edit', compact('announcement'));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, Announcement $announcement)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ], [
        'title.required' => 'Vui lòng nhập tiêu đề.',
        'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
        'content.required' => 'Vui lòng nhập nội dung.',
    ]);

    $announcement->update([
        'title' => $request->title,
        'content' => $request->content,
        'is_published' => $request->has('is_published'),
    ]);

    return redirect()
        ->route('announcements.index')
        ->with('success', 'Cập nhật thông báo thành công!');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function destroy(Announcement $announcement)
{
    $announcement->delete();

    return redirect()
        ->route('announcements.index')
        ->with('success', 'Xóa thông báo thành công!');
}
}
