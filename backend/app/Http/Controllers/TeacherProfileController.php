<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherProfileController extends Controller
{
    /**
     * Đổi mật khẩu tài khoản giáo viên.
     */
    public function updatePassword(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate dữ liệu
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'current_password' => [
                    'required',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ],
            [
                'current_password.required' =>
                    'Vui lòng nhập mật khẩu hiện tại.',

                'password.required' =>
                    'Vui lòng nhập mật khẩu mới.',

                'password.min' =>
                    'Mật khẩu mới phải có ít nhất 8 ký tự.',

                'password.confirmed' =>
                    'Xác nhận mật khẩu mới không khớp.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Lấy tài khoản đang đăng nhập
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Kiểm tra mật khẩu hiện tại
        |--------------------------------------------------------------------------
        */

        if (
            !Hash::check(
                $request->current_password,
                $user->password
            )
        ) {

            return back()
                ->withErrors([
                    'current_password' =>
                        'Mật khẩu hiện tại không chính xác.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Lưu mật khẩu mới
        |--------------------------------------------------------------------------
        |
        | Mật khẩu được mã hóa trước khi lưu vào users.password.
        |
        */

        $user->password = Hash::make(
            $request->password
        );

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | Thông báo thành công
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Đổi mật khẩu thành công.'
        );
    }
}