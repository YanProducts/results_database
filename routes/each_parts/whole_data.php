<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WholeData\AuthController;

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::prefix("whole_data")
      ->name("whole_data.")
      ->controller(AuthController::class)
      ->middleware(['web'])->group(function(){      
      // この部分は他のファイルと同じだが、設計図自体は外注しないのがLaravel的

      // 営業所長新規登録ページの表示
      Route::get("register","show_register")
      ->name("register");
      // 営業所長ログインページの表示
      Route::get("login","show_login")
      ->name("login");
      // 営業所長パスワード変更ページ
      Route::get("pass_change","show_pass_change")
      ->name("pass_change");
      // 営業所長新規登録ページの投稿
      Route::post("register","post_register")
      ->name("register_post");
      // 営業所長ログインページの投稿
      Route::post("login","post_login")
      ->name("login_post");
      // 営業所長パスワード変更ページ
      Route::post("pass_change","post_pass_change")
      ->name("pass_change_post");
});