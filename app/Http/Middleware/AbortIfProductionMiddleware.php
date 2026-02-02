<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AbortIfProductionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // 本番環境なら権限不可で返すミドルウェア
    public function handle(Request $request, Closure $next): Response
    {
        if(app()->isProduction()){
            abort(403);
        }
        return $next($request);
    }
}
