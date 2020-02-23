<?php

namespace Modules\User\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $guard = \Auth::guard($guard);
        if (!$guard->check()) {
            return redirect()->guest(route('admin.login'))->withErrors('Vui lòng đăng nhập');
        }
        /*if (!$guard->user()->hasRole('admin')) {
            $guard->logout();
            return redirect()->guest(route('admin.login'))->withErrors('Vui lòng đăng nhâp bằng tài khoản quản trị!');
        }*/
        return $next($request);
    }
}
