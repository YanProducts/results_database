<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WholeData\SettingController;

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::prefix("whole_data")
      ->name("whole_data.")
      ->middleware(['web'])
      ->group(function(){
            Route::controller(AuthController::class)
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
        Route::middleware(["redirectWholeDataUnAuth"])
            ->group(function(){
                // スタッフ/事務担当/営業所/営業担当の登録系統
                Route::controller(SettingController::class)
                ->group(function(){
                    // 営業所の登録ページ表示
                    Route::get("register_places","register_places")
                    ->name("register_places");

                    // 営業所の登録投稿
                    Route::post("register_places","register_places_post")
                    ->name("register_places_post");

                    // スタッフ/事務担当/営業所/営業担当/案件担当の登録(一覧)
                    Route::get("provision","provision")
                    ->name("provision");
                    // スタッフ/事務担当/営業所/営業担当/案件担当の登録(決定)
                    Route::post("provision","provision_post")
                    ->name("provision_post");
                    // 編集

                    // 削除

                });
                // ログアウト(そもそも認証されていないと無理)
                Route::get("logout",[AuthController::class,"logout"])
                ->name("logout");
            });
});
