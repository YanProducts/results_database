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

    // 1つ以上のroleが格納され、...は可変長変数(*配列展開のスプレッド構文ではない)で、変数を1つの配列として格納
    public function handle(Request $request, Closure $next,...$roles): Response
    {
        // Auth::user()で現在認証中のモデルインスタンスが返る。UserAuthがAuthを継承し、そこのgetRoleAttributeでroleプロパティにアクセスできるよう設定済み
        foreach($roles as $role)
        if(Auth::user()->role===$role){
            // 認証されていたらOK
            return $next($request);
        }

        // 向かう先のパスにどのワードが含まれているかで、どのログインページに返すかが決まる
        return RedirectLoginPage::redirect_login_page_func($request);
    }
}
