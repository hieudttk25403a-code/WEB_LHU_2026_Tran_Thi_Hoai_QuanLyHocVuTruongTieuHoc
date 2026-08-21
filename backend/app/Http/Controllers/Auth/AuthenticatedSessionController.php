<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | XÁC THỰC ĐĂNG NHẬP
        |--------------------------------------------------------------------------
        */

        $request->authenticate();

        /*
        |--------------------------------------------------------------------------
        | TẠO SESSION MỚI
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | LẤY USER ĐANG ĐĂNG NHẬP
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | PHÂN QUYỀN VÀ ĐIỀU HƯỚNG
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'teacher') {

            return redirect()->route('teacher.dashboard');
        }

        if ($user->role === 'bgh') {

            return redirect()->route('bgh.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | TRƯỜNG HỢP TÀI KHOẢN CHƯA CÓ QUYỀN HỢP LỆ
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Tài khoản chưa được phân quyền hợp lệ.',
            ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}