<?php

namespace App\Http\Middleware;

use App\Support\Auth\RedirectLoginPage;
use App\Support\Auth\UserRoleResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfUserInActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // 非稼働のユーザーはログイン後のページを操作できないようにする
    // Authは順序的に検証済みとする
    public function handle(Request $request, Closure $next,$role): Response
    {
        // roleのモデルの取得
        if(!UserRoleResolver::check_user_is_active($role,Auth::user()->authable_id)){
            // ログインページに戻す
            return RedirectLoginPage::redirect_login_page_func($request);

        }


        return $next($request);
    }
}
