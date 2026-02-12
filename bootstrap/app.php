<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 既存のwebミドルウェアにappend
        $middleware->web(
           append: [
            // inertiaを通じさせる
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
        $middleware->alias([
            // authの登録先を定める
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            // 認証されていない場合はログインへ
            "redirectUnAuth"=>\App\Http\Middleware\RedirectIfUnAuthenticated::class,
            // 認証されていても認証先が違う場合はそのログインページへ(authは1つしか無理なので、ログアウトは別に行う)
            "redirectUnMatchedRole"=>\App\Http\Middleware\RedirectIfUnMatchedRole::class,
            // 開発環境以外は通さない(SQL挿入など)
            "onlyLocal"=>\App\Http\Middleware\AbortIfProductionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //エラールート
        
    })->create();
