<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchManager\ProjectHandingController;
use App\Http\Controllers\BranchManager\ProjectRecordController;
use App\Http\Controllers\BranchManager\StaffAssignmentController;
use Inertia\Inertia;

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::prefix("branch_manager")
      ->name("branch_manager.")
      ->middleware(['web'])
      ->group(function(){
          Route::controller(AuthController::class)
            ->group(function(){
            // この部分は他のファイルと同じだが、設計図自体は外注しないのがLaravel的
            // 営業所担当新規登録ページの表示
            Route::get("register","show_register")
            ->name("register");
            // 営業所担当ログインページの表示
            Route::get("login","show_login")
            ->name("login");
            // 営業所担当パスワード変更ページ
            Route::get("pass_change","show_pass_change")
            ->name("pass_change");
            // 営業所担当新規登録ページの投稿
            Route::post("register","post_register")
            ->name("register_post");
            // 営業所担当ログインページの投稿
            Route::post("login","post_login")
            ->name("login_post");
            // 営業所担当パスワード変更ページ
            Route::post("pass_change","post_pass_change")
            ->name("pass_change_post");
            });
    // 担当の決定を行うページへ(認証や違う認証の場合は現場用のログインページへ)
      Route::middleware(["redirectUnAuth","redirectUnMatchedRole:branch_manager"])
        ->group(function(){
            Route::controller(StaffAssignmentController::class)
            ->group(function(){
                  // 担当/案件/町目/日付の割り当てのトップへ
                  Route::get("assignment","assign_staff")
                  ->name("assign_staff");
                  // 担当/案件/町目/日付の割り当ての決定
                  Route::post("assignment","assignment_post")
                  ->name("assign_staff_post");
            });
            // 案件を自分で登録する系統
            Route::controller(ProjectHandingController::class)
            ->group(function(){

            });
            Route::controller(ProjectRecordController::class)
            ->group(function(){
            // 過去の配布データを確認する方法

            });
         // トップページへ(担当の決定やデータチェックも含む)
         Route::get("top_page",function(){
            return Inertia::render("BranchManager/TopPage",[
                "what"=>"営業所担当",
                "type"=>"トップ"
            ]);
         })
        ->name("top_page");
         // ログアウト(そもそも認証されていないと無理)
         Route::get("logout",[AuthController::class,"logout"])
         ->name("logout");
        });
    });
