<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Support\Auth\RedirectLoginPage;

class RedirectIfUnMatchedRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // 直接トップページに行こうとした際に、すでにauthは認証済みだったが、違う入り口での認証許可の可能性もあるので、その場合はそのトップページに戻すミドルウェア

    public function handle(Request $request, Closure $next,$role): Response
    {
        // Auth::user()で現在認証中のモデルインスタンスが返る。UserAuthがAuthを継承し、そこのgetRoleAttributeでroleプロパティにアクセスできるよう設定済み
        if(Auth::user()->role!==$role){
            // 違う入り口の認証は遮断する必要ない。なぜならば、Auth::loginでログインできるユーザーは１人のため、ログインで更新できるから。

            // 向かう先のパスにどのワードが含まれているかで、どのログインページに返すかが決まる
            return RedirectLoginPage::RedirectLoginPageFunc($request);
        }

        return $next($request);
    }
}
