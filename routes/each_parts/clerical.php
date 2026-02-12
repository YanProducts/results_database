<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Clerical\WriteReportController;
use App\Http\Controllers\Clerical\ExportController;

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::prefix("clericals")
      ->name("clericals.")
      ->group(function(){
        Route::controller(AuthController::class)
         ->middleware(['web'])->group(function(){
            // この部分は他のファイルと同じだが、設計図自体は外注しないのがLaravel的
            // 事務担当新規登録ページの表示
            Route::get("register","show_register")
            ->name("register");
            // 事務担当ログインページの表示
            Route::get("login","show_login")
            ->name("login");
            // 事務担当パスワード変更ページ
            Route::get("pass_change","show_pass_change")
            ->name("pass_change");
            // 事務担当新規登録ページの投稿
            Route::post("register","post_register")
            ->name("register_post");
            // 事務担当ログインページの投稿
            Route::post("login","post_login")
            ->name("login_post");
            // 事務担当パスワード変更ページ
            Route::post("pass_change","post_pass_change")
            ->name("pass_change_post");
        });
      })
    ->group(function(){
        // 入力を行うページへ(認証や違う認証の場合は現場用のログインページへ)
        Route::middleware(['web',"redirectUnAuth","redirectUnMatchedRole:clerical"])
            ->group(function(){
                Route::controller(WriteReportController::class)
                 ->group(function(){
                        // トップページへ(報告書入力以外に発注書作成なども考える)
                        Route::get("top_page","top_page")
                        ->name("top_page");


                        // 報告書作成(入力担当用)
                        Route::get("write_report","write_report")
                        ->name("write_report");
                        // 報告書提出(入力担当用)
                        Route::post("write_report","post_write_report")
                        ->name("write_report_post");
                  });
                Route::controller(ExportController::class)
                 ->group(function(){
                        // 報告書エクスポート確認（どれをエクスポートするか）
                        Route::get("export_report","export_report")
                        ->name("write_report");
                        // 報告書提出(入力担当用)
                        Route::post("export_report","decide_export_report")
                        ->name("write_report_post");
                        // 発注書のエクスポート確認
                        Route::get("export_purchase_order","export_purchase_order")
                        ->name("export_purchase_order");
                    });
             });
    });
