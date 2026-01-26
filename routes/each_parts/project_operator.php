<?php
// 案件データを入れる役割
// このディレクトリは他のディレクトリと統合の可能性あり

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchManager\AuthController;
use App\Http\Controllers\BranchManager\StaffAssignmentController;

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::prefix("project_operator")
      ->name("project_operator.")
      ->controller(AuthController::class)
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

// 担当の決定を行うページへ(認証や違う認証の場合は現場用のログインページへ)
Route::prefix("project_operator")
      ->name("project_operator.")
      ->controller(StaffAssignmentController::class)
      ->middleware(['web',"redirectUnAuth","redirectUnMatchedRole"])
      ->group(function(){


});