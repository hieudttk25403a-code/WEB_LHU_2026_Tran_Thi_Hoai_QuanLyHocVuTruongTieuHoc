<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Kiểm tra quyền truy cập của tài khoản.
     *
     * Cách sử dụng:
     *
     * ->middleware('role:admin')
     * ->middleware('role:teacher')
     * ->middleware('role:bgh')
     *
     * Có thể cho phép nhiều quyền:
     *
     * ->middleware('role:admin,bgh')
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        // Chưa đăng nhập
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Nếu route không truyền quyền hợp lệ
        if (empty($roles)) {
            abort(403, 'Chưa xác định quyền truy cập.');
        }

        // Kiểm tra role của tài khoản
        if (!in_array($user->role, $roles, true)) {
            abort(
                403,
                'Bạn không có quyền truy cập chức năng này.'
            );
        }

        return $next($request);
    }
}