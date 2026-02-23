<?php

use App\Exceptions\BusinessException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


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
            // 全般管理データのログイン認証
            "redirectWholeDataUnAuth"=>\App\Http\Middleware\RedirectIfWholeDataUnAuthenticated::class,
            // 開発環境以外は通さない(SQL挿入など)
            "onlyLocal"=>\App\Http\Middleware\AbortIfProductionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function(Response $response,Throwable $e, Request $request){
            // fetch送信は適用させない
            // 直リンクでの404や403のエラーはそのままbladeで表示(リンクが違った場合などInertia絡みのものだけ遷移される)
            if ($request->header('X-Inertia')){
                $http_responce_code=$response->getStatusCode();
                $error_message=match(true){
                    $http_responce_code>=500=>(app()->isLocal() || $e instanceof BusinessException) ? $e->getMessage() : "システムエラーです",
                    $http_responce_code==404=>"リンク先が不明です",
                    $http_responce_code==403=>"このページを見る権限がありません",
                    default=>"予期せぬエラーです"
                };

                if($http_responce_code>=500 || in_array($http_responce_code,[403,404])){
                    return redirect()->route("view_error")->with(["error_message"=>$error_message]);
                }
            }
            // 404や403は拾わず、SQL,文法,自分で投げたthrowのエラーなどを披露
            // if($response->getStatusCode()>=500){
            //     return redirect()->route("view_error")->with(["error_message"=>(app()->isLocal() || $e instanceof BusinessException) ? $e->getMessage() : "システムエラーです"]);
            // }
            return $response;
        });

    })->create();
