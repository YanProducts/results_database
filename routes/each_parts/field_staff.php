<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FieldStaffs\ReportManagementController;
use App\Http\Controllers\FieldStaffs\WriteReportController;


// 現場スタッフに関するルーティング

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)

// 認証関連
Route::prefix("field_staff")
      ->name("field_staff.")
      ->middleware(['web'])
      ->group(function(){
          Route::controller(AuthController::class)
          ->group(function(){
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

          //   シフトサイトからの連動


           });
        // 認証後の操作
        Route::middleware(["redirectUnAuth","redirectUnMatchedRole:field_staff"])
            ->group(function(){
                Route::controller(WriteReportController::class)
                  ->group(function(){
                    // 報告書作成
                    Route::get("write_report","write_report")
                    ->name("write_report");
                    // 報告書提出
                    Route::post("write_report","write_report_post")
                    ->name("write_report_post");

                });
            Route::controller(ReportManagementController::class)
                ->group(function(){
                    // 報告書確認/編集(日付リスト) //monthパラメータは何か月前か
                    Route::get("overview_reports/{year?}/{month?}","overview_reports")
                    ->name("overview_reports");
                    // 報告書の日付リスト→日付を指定して確認
                    Route::get("show_detail_report/{date}","show_detail_report")
                    ->name("show_detail_report");
                    // 上記の日付で編集
                    Route::get("edit_report/{date}","edit_report")
                    ->name("edit_report_post");
                    // 上記の日付で編集の投稿
                    Route::post("edit_report","edit_report_post")
                    ->name("edit_report_post");
                });



            // ログアウト(そもそも認証されていないと無理)
            Route::get("logout",[AuthController::class,"logout"])
            ->name("logout");
    });
});
