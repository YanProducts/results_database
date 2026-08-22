<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchManager\AssignOverviewContorller;
use App\Http\Controllers\BranchManager\ReportManagementController;
use App\Http\Controllers\BranchManager\ProjectHandingController;
use App\Http\Controllers\BranchManager\ProjectRecordController;
use App\Http\Controllers\BranchManager\SimpleAssignMentController;
use App\Http\Controllers\BranchManager\StaffAssignmentController;
use App\Http\Controllers\FieldStaffs\ReportManagementController as ReportManagementFromFieldStaff;
use Illuminate\Support\Facades\Log;
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
                  Route::get("assign_staff","assign_staff")
                  ->name("assign_staff");
                  // 担当/案件/町目/日付の割り当ての決定
                  Route::post("assign_staff","assign_staff_post")
                  ->name("assign_staff_post");
                 //町目の重複確認でOKだった時
                 Route::post("store_including_duplicated_plans","store_including_duplicated_plans")
                 ->middleware(["assignStaffDuplicatedCheck"])
                 ->name("store_including_duplicated_plans");
            });
            // 簡易版(地図のみから選択)の
            Route::controller(SimpleAssignMentController::class)
            ->group(function(){
                  // 簡易：担当/案件/町目/日付の割り当てのトップへ
                  Route::get("simple_assign_staff","assign_staff")
                  ->name("simple_assign_staff");
                  // 担当/案件/町目/日付の割り当ての決定
                  Route::post("simple_assign_staff","assign_staff_post")
                  ->name("simple_assign_staff_post");
                 //町目の重複確認でOKだった時
                 Route::post("simple_store_including_duplicated_plans","store_including_duplicated_plans")
                 ->middleware(["assignStaffDuplicatedCheck"])
                 ->name("simple_store_including_duplicated_plans");
            });
            // 振った案件の確認と修正
            Route::controller(AssignOverviewContorller::class)
            ->group(function(){
                    // 現在の担当の確認
                    Route::get("assign_overview","assign_overview")
                    ->name("assign_overview");
                    // 日の選択→編集
                    Route::post("edit_assign","edit_assign")
                    ->name("edit_assign");
            });

            // 報告書の修正や営業所担当側での記入
            Route::controller(ReportManagementController::class)
            ->group(function(){

                // 報告書の確認or代替記入(スタッフから)
                Route::get("choice_report_target","choice_report_target")
                ->name("choice_report_target");

                 // 報告書の確認or代替記入(人の決定→日付表示画面へ)
                Route::post("choice_report_target","choice_report_target_post")
                ->name("choice_report_target_post");


                // 報告書の確認or代替記入する日付の決定→確認
                Route::post("decide_date_for_report_choice","decide_date_for_report_choice_post")
                ->name("decide_date_for_report_choice_post");


                // 上記投稿時にバリデーションで返った時(あってる！？？？)
                Route::get("decide_date_for_report_choice","decide_date_for_report_choice_post")
                ->name("decide_date_for_report_choice");




                // 報告書の確認or代替記入(日付から)
                Route::get("choice_report_date_target","choice_report_date_target")
                ->name("choice_report_date_target");
                // 報告書の確認or代替記入(日付決定の投稿)
                Route::post("choice_report_date_target","choice_report_date_target_post")
                ->name("choice_report_date_target_post");
                // 報告書の確認or代替記入(日付決定後、スタッフの選択の決定)
                Route::post("decide_staff_for_report_choice","decide_staff_for_report_choice_post")
                ->name("decide_date_for_report_choice_post");



                // 報告書の代替記入(人の決定後、報告書表示画面=errorsで戻った時のことを考えInertiaではなく完全にredirectさせる)
                //人の選択がない状態ではエラーにすること
                Route::get("complete_report","complete_report")
                ->name("complete_report");
                // 報告書の代替記入(最終決定)
                Route::post("complete_report","complete_report_post")
                ->name("complete_report_post");
                // 報告書の編集(選択)
                Route::get("choice_edit_report_target","choice_edit_report_target")
                ->name("choice_edit_report_target");
                // 報告書の編集(決定して編集できる状態に)
                Route::post("choice_edit_report_target","choice_edit_report_target_post")
                ->name("choice_edit_report_target_post");
                // 上記にバリデーションで戻った時
                Route::get("choice_edit_report_target","choice_edit_report_target")
                ->name("choice_edit_report_target");
            });

            // 報告書の確認or代替記入(日付画面表示へ)
            // スタッフのコントローラーを使うので別途定義
            Route::controller(ReportManagementFromFieldStaff::class)->group(function(){
                    Route::get("overview_reports/{year?}/{month?}","overview_reports")
                    ->name("overview_reports");
            });

            // 案件を営業所側で登録する系統
            Route::controller(ProjectHandingController::class)
            ->group(function(){
            // その営業所における案件の登録
                Route::get("handing_assignment","handing_assignment")
                ->name("handing_assignment");
            });

            // 町丁目データを見る
            Route::controller(ProjectRecordController::class)
            ->group(function(){
               // 過去の配布データを確認(町々目選択)
                Route::get("confirm_project_record","confirm_project_record")
                ->name("confirm_project_record");
               // 過去の配布データの確認(町々目選択後)
                Route::post("confirm_project_record","confirm_project_record_post")
                ->name("confirm_project_record_post");

                // その営業所特有の備考欄つけるか！？


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
