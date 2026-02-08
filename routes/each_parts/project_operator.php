<?php
// 案件データを入れる役割
// このディレクトリは他のディレクトリと統合の可能性あり

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// 案件担当者のできること一覧のコントローラー
use App\Http\Controllers\ProjectOperator\ActionSelectController;

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::prefix("project_operator")
      ->name("project_operator.")
      ->group(function(){
          Route::controller(AuthController::class)
          ->middleware(['web'])->group(function(){
            // この部分は他のファイルと同じだが、設計図自体は外注しないのがLaravel的
            // 案件入力担当新規登録ページの表示
            Route::get("register","show_register")
            ->name("register");
            // 案件入力担当ログインページの表示
            Route::get("login","show_login")
            ->name("login");
            // 案件入力担当パスワード変更ページ
            Route::get("pass_change","show_pass_change")
            ->name("pass_change");
            // 案件入力担当新規登録ページの投稿
            Route::post("register","post_register")
            ->name("register_post");
            // 案件入力担当ログインページの投稿
            Route::post("login","post_login")
            ->name("login_post");
            // 案件入力担当パスワード変更ページ
            Route::post("pass_change","post_pass_change")
            ->name("pass_change_post");
      });
    })
    ->group(function(){
        // 案件のCrud操作を行うページへ(認証や違う認証の場合は案件担当用のログインページへ)
        Route::controller(ActionSelectController::class)
            ->middleware(['web',"redirectUnAuth","redirectUnMatchedRole:project_operator"])
            ->group(function(){
            //   案件をどう操作するかのリスト
                Route::get("action_select","action_select")
                ->name("action_select");
            //   案件をどう操作するかの決定
                Route::post("action_select","action_select")
                ->name("action_select_post");
      });
    });


