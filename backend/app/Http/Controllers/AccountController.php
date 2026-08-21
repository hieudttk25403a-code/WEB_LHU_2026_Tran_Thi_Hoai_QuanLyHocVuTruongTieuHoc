<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Danh sách tài khoản.
     */
    public function index()
    {
        $accounts = User::with('teacher')
            ->latest()
            ->paginate(10);

        return view(
            'admin.accounts.index',
            compact('accounts')
        );
    }


    /**
     * Form thêm tài khoản.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Chỉ lấy giáo viên chưa có tài khoản
        |--------------------------------------------------------------------------
        */

        $teachers = Teacher::whereDoesntHave('user')
            ->orderBy('full_name')
            ->get();

        return view(
            'admin.accounts.create',
            compact('teachers')
        );
    }


    /**
     * Lưu tài khoản mới.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Xác định loại tài khoản
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'role' => [
                'required',
                Rule::in(['teacher', 'bgh']),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'role.required' =>
                'Vui lòng chọn loại tài khoản.',

            'password.required' =>
                'Vui lòng nhập mật khẩu.',

            'password.min' =>
                'Mật khẩu phải có ít nhất 8 ký tự.',

            'password.confirmed' =>
                'Xác nhận mật khẩu không khớp.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | TRƯỜNG HỢP GIÁO VIÊN
        |--------------------------------------------------------------------------
        */

        if ($request->role === 'teacher') {

            $request->validate([
                'teacher_id' => [
                    'required',
                    'exists:teachers,id',
                ],
            ], [
                'teacher_id.required' =>
                    'Vui lòng chọn giáo viên.',

                'teacher_id.exists' =>
                    'Giáo viên không tồn tại.',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Lấy giáo viên
            |--------------------------------------------------------------------------
            */

            $teacher = Teacher::find(
                $request->teacher_id
            );


            if (!$teacher) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Không tìm thấy giáo viên.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Kiểm tra giáo viên đã có tài khoản chưa
            |--------------------------------------------------------------------------
            */

            if ($teacher->user()->exists()) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Giáo viên này đã được cấp tài khoản.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Giáo viên bắt buộc phải có email
            |--------------------------------------------------------------------------
            */

            if (empty($teacher->email)) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Giáo viên này chưa có email. Vui lòng cập nhật email trong Quản lý giáo viên trước khi cấp tài khoản.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Kiểm tra email giáo viên đã được dùng chưa
            |--------------------------------------------------------------------------
            */

            if (
                User::where(
                    'email',
                    $teacher->email
                )->exists()
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Email của giáo viên này đã được sử dụng cho một tài khoản khác.'
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Tạo tài khoản giáo viên
            |--------------------------------------------------------------------------
            |
            | QUAN TRỌNG:
            |
            | name  = teachers.full_name
            | email = teachers.email
            | teacher_id = teachers.id
            |
            */

            User::create([
                'name' => $teacher->full_name,

                'email' => $teacher->email,

                'password' => Hash::make(
                    $request->password
                ),

                'role' => 'teacher',

                'teacher_id' => $teacher->id,
            ]);


            return redirect()
                ->route('admin.accounts.index')
                ->with(
                    'success',
                    'Cấp tài khoản cho giáo viên ' .
                    $teacher->full_name .
                    ' thành công.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TRƯỜNG HỢP BAN GIÁM HIỆU
        |--------------------------------------------------------------------------
        */

        if ($request->role === 'bgh') {

            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],
            ], [
                'name.required' =>
                    'Vui lòng nhập họ và tên.',

                'email.required' =>
                    'Vui lòng nhập email.',

                'email.email' =>
                    'Email không đúng định dạng.',

                'email.unique' =>
                    'Email này đã được sử dụng.',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Tạo tài khoản BGH
            |--------------------------------------------------------------------------
            */

            User::create([
                'name' => $validated['name'],

                'email' => $validated['email'],

                'password' => Hash::make(
                    $request->password
                ),

                'role' => 'bgh',

                'teacher_id' => null,
            ]);


            return redirect()
                ->route('admin.accounts.index')
                ->with(
                    'success',
                    'Tạo tài khoản Ban giám hiệu thành công.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Trường hợp không xác định
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput()
            ->withErrors([
                'role' =>
                    'Loại tài khoản không hợp lệ.'
            ]);
    }


    /**
     * Xem chi tiết tài khoản.
     */
    public function show(User $account)
    {
        $account->load('teacher');

        return view(
            'admin.accounts.show',
            compact('account')
        );
    }


    /**
     * Form chỉnh sửa tài khoản / phân quyền.
     */
    public function edit(User $account)
    {
        $teachers = Teacher::where(function ($query) use ($account) {

            $query->whereDoesntHave('user')

                ->orWhereHas(
                    'user',
                    function ($userQuery) use ($account) {

                        $userQuery->where(
                            'users.id',
                            $account->id
                        );

                    }
                );

        })
        ->orderBy('full_name')
        ->get();


        return view(
            'admin.accounts.edit',
            compact(
                'account',
                'teachers'
            )
        );
    }


    /**
     * Cập nhật tài khoản / phân quyền.
     */
    public function update(
        Request $request,
        User $account
    ) {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique(
                    'users',
                    'email'
                )->ignore($account->id),
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'teacher',
                    'bgh'
                ]),
            ],

            'teacher_id' => [
                'nullable',
                'exists:teachers,id',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Không cho Admin tự hạ quyền chính mình
        |--------------------------------------------------------------------------
        */

        if (
            $account->id === auth()->id()
            &&
            $validated['role'] !== 'admin'
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'role' =>
                        'Bạn không thể tự hạ quyền tài khoản Admin đang đăng nhập.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Nếu chuyển sang Giáo viên
        |--------------------------------------------------------------------------
        */

        if ($validated['role'] === 'teacher') {

            if (empty($validated['teacher_id'])) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Vui lòng chọn giáo viên.'
                    ]);
            }


            $teacher = Teacher::find(
                $validated['teacher_id']
            );


            if (!$teacher) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Giáo viên không tồn tại.'
                    ]);
            }


            /*
            | Không cho giáo viên thuộc tài khoản khác
            */

            if (
                $teacher->user
                &&
                $teacher->user->id !== $account->id
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Giáo viên này đã được cấp tài khoản khác.'
                    ]);
            }


            /*
            | Email phải khớp với email hồ sơ giáo viên
            */

            if (empty($teacher->email)) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Giáo viên này chưa có email.'
                    ]);
            }


            $validated['name'] =
                $teacher->full_name;

            $validated['email'] =
                $teacher->email;

            $validated['teacher_id'] =
                $teacher->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Nếu là Admin hoặc BGH
        |--------------------------------------------------------------------------
        */

        else {

            $validated['teacher_id'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Cập nhật
        |--------------------------------------------------------------------------
        */

        $account->name =
            $validated['name'];

        $account->email =
            $validated['email'];

        $account->role =
            $validated['role'];

        $account->teacher_id =
            $validated['teacher_id'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Chỉ đổi mật khẩu nếu nhập mật khẩu mới
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['password'])) {

            $account->password =
                Hash::make(
                    $validated['password']
                );
        }


        $account->save();


        return redirect()
            ->route('admin.accounts.index')
            ->with(
                'success',
                'Cập nhật tài khoản thành công.'
            );
    }


    /**
     * Xóa tài khoản.
     */
    public function destroy(User $account)
    {
        /*
        |--------------------------------------------------------------------------
        | Không cho Admin tự xóa mình
        |--------------------------------------------------------------------------
        */

        if ($account->id === auth()->id()) {

            return back()
                ->with(
                    'error',
                    'Bạn không thể xóa tài khoản đang đăng nhập.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Xóa tài khoản
        |--------------------------------------------------------------------------
        |
        | Chỉ xóa User.
        |
        | Không xóa Teacher.
        |
        */

        $account->delete();


        return redirect()
            ->route('admin.accounts.index')
            ->with(
                'success',
                'Xóa tài khoản thành công.'
            );
    }
}