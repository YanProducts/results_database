<?php

namespace App\Http\Middleware;

use App\Support\Auth\RedirectLoginPage;
use App\Support\Auth\UserRoleResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfPlaceInActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // 非稼働の営業所に所属するユーザーのページを操作できないようにする
    public function handle(Request $request, Closure $next,$role): Response
    {
        // roleのモデルの取得
        if(!UserRoleResolver::check_place_is_active($role,Auth::user()->authable_id)){

            //ログインルートに戻すfunction
            return RedirectLoginPage::redirect_login_page_func($request);
        }

        return $next($request);
    }
}
