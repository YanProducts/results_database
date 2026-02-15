<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfWholeDataUnAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // whole_dataの
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->session()->has("whole_data_auth")){
            // ログインしていなければ、全体統括用のログインページへ返却
            return redirect()->route("whole_data.login");
        }

        return $next($request);
    }
}
