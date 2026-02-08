<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WholeData\RegisterController;
use App\Http\Controllers\WholeData\SettingsController;

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::prefix("whole_data")
      ->name("whole_data.")
      ->group(function(){
            Route::controller(AuthController::class)
                ->middleware(['web'])
                ->group(function(){
                    // この部分は他のファイルと同じだが、設計図自体は外注しないのがLaravel的
                    // 全体操作新規登録ページの表示
                    //   本来はここにも「既に登録されていたら戻る」のミドルウェアを入れたい！
                    Route::get("register","show_register")
                    ->name("register");
                    // 全体操作ログインページの表示
                    Route::get("login","show_login")
                    ->name("login");
                    // 全体操作パスワード変更ページ
                    Route::get("pass_change","show_pass_change")
                    ->name("pass_change");

                    // 全体操作新規登録ページの投稿(仮登録)
                    //   本来はここにも「既に登録されていたら戻る」のミドルウェアを入れたい！
                    Route::post("register","post_register")
                    ->name("register_post");

                    // 全体操作ログインページの投稿
                    Route::post("login","post_login")
                    ->name("login_post");
                    // 全体操作パスワード変更ページ
                    Route::post("pass_change","post_pass_change")
                    ->name("pass_change_post");
                    // メールのURLクリックで仮登録→本登録(tokenはrequestで取得)
                    Route::get("create_user","create_whole_data_administer")
                    ->name("create_user");
                });
    })->group(function(){
        Route::middleware(["web","redirectUnAuth","redirectUnMatchedRole:whole_data"])
            ->group(function(){
                // スタッフ/事務担当/営業所/営業担当の登録系統
                Route::controller(RegisterController::class)
                ->group(function(){
                    // スタッフ/事務担当/営業所/営業担当/案件担当の登録(一覧)
                    Route::get("select","select")
                    ->name("select");
                    // スタッフ/事務担当/営業所/営業担当/案件担当の登録(決定)
                    Route::post("select","select")
                    ->name("select_post");
                });
                //  全般管理操作
                Route::controller(SettingsController::class)
                ->group(function(){


                });
            });
});
