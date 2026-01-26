<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FieldStaffs\AuthController;
use App\Http\Controllers\FieldStaffs\WriteReportController;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RedirectIfUnMatchedRole;


// 現場スタッフに関するルーティング

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)

// 認証関連
Route::prefix("field_staff")
      ->name("field_staff.")      
      ->controller(AuthController::class)
      ->middleware(['web'])->group(function(){      
      // この部分は他のファイルと同じだが、設計図自体は外注しないのがLaravel的
      // 現場担当者新規登録ページの表示
      Route::get("register","show_register")
      ->name("register");
      // 現場担当者ログインページの表示
      Route::get("login","show_login")
      ->name("login");
      // 現場担当者パスワード変更ページ
      Route::get("pass_change","show_pass_change")
      ->name("pass_change");
      // 現場担当者新規登録ページの投稿
      Route::post("register","post_register")
      ->name("register_post");
      // 現場担当者ログインページの投稿
      Route::post("login","post_login")
      ->name("login_post");
      // 現場担当者パスワード変更ページ
      Route::post("pass_change","post_pass_change")
      ->name("pass_change_post");
});

// 入力を行うページへ(認証や違う認証の場合は現場用のログインページへ)
Route::prefix("field_staff")
      ->name("field_staff.")
      ->controller(WriteReportController::class)
      ->middleware(['web',"redirectUnAuth","redirectUnMatchedRole"])
      ->group(function(){
            // 報告書作成
            Route::get("write_report","write_report")
            ->name("write_report");
            // 報告書提出
            Route::post("write_report","post_write_report")
            ->name("write_report_post");
});