<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WholeData\AuthController;
use App\Http\Controllers\WholeData\RegisterController;
use App\Http\Controllers\WholeData\SettingsController;

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::prefix("whole_data")
      ->name("whole_data.")
      ->group(function(){
        Route::controller(AuthController::class)
      ->middleware(['web'])->group(function(){
      // この部分は他のファイルと同じだが、設計図自体は外注しないのがLaravel的

      // 全体操作新規登録ページの表示
      Route::get("register","show_register")
      ->name("register");
      // 全体操作ログインページの表示
      Route::get("login","show_login")
      ->name("login");
      // 全体操作パスワード変更ページ
      Route::get("pass_change","show_pass_change")
      ->name("pass_change");
      // 全体操作新規登録ページの投稿
      Route::post("register","post_register")
      ->name("register_post");
      // 全体操作ログインページの投稿
      Route::post("login","post_login")
      ->name("login_post");
      // 全体操作パスワード変更ページ
      Route::post("pass_change","post_pass_change")
      ->name("pass_change_post");
      });
})->group(function(){
    Route::middleware(["web","redirectUnAuth","redirectUnMatchedRole"])
        ->group(function(){
            // スタッフ/事務担当/営業所/営業担当の登録系統
            Route::controller(RegisterController::class)
             ->group(function(){
                // スタッフ/事務担当/営業所/営業担当/案件担当の登録(一覧)
                Route::get("register","register_select")
                ->name("register_select");
                // スタッフ/事務担当/営業所/営業担当/案件担当の登録(決定)
                Route::post("register","register_select")
                ->name("register_select_post");
             });
            //  全般管理操作
            Route::controller(SettingsController::class)
            ->group(function(){

            });
        });
});
