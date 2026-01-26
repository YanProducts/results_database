<?php

namespace App\Http\Middleware;

use App\Support\RedirectLoginPage;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfUnAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // トップページにダイレクトで入ろうとした時に、１Auth認証２それがそのページに紐づいたものかを両方チェックする。そのうちの「認証」の部分がここ
    public function handle(Request $request, Closure $next): Response
    {
        // 認証されていない場合
        if(!Auth::check() ){    
            // 向かう先のパスにどのワードが含まれているかで、どのログインページに返すかが決まる
            return RedirectLoginPage::RedirectLoginPageFunc($request);
      }
    return $next($request);
    }
}
