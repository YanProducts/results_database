<?php

namespace App\Http\Controllers\BranchManager;

use App\Actions\BranchManager\Report\GetDataInStaffAndDate;
use App\Actions\BranchManager\Report\GetOverviewByDay;
use App\Constants\Date;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchManager\ChoiceFromStaffRequest;
use App\Http\Requests\BranchManager\ReportChoiceDecideRequest;
use App\Models\BranchManagerList;
use App\Models\FieldStaffList;
use App\Models\UserAuth;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Utils\DateHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReportManagementController extends Controller
{
    // 報告書の確認or代替記入(スタッフから)
    public function choice_report_target()
    {
        return Inertia::render("BranchManager/ReportManagement/StaffToDate/ChoiceFromStaff",[
            "what"=>"営業所担当",
            "type"=>"報告書確認",
            // 営業所にいるスタッフのデータ
            "staffs"=>(FieldStaffListHelpers::get_all_names_of_staffs_in_the_place(BranchManagerListHelpers::get_login_user_place_id()))->map(fn($each_staff_data)=>[
            "id"=>$each_staff_data["id"],
            "nameForUI"=>$each_staff_data["staff_name"]
            ])
        ]);
    }


    // 報告書の確認or代替記入(人の決定→日付選択画面へ)
    public function choice_report_target_post(ChoiceFromStaffRequest $request)
    {
        // スタッフの取得
        $staff_ids=$request->staffs;

        // 日付の範囲のセットを取得
        $date_sets=
        (empty($year) || empty($month)) ?
        DateHelper::get_date_key_value_sets_for_view(Carbon::now()->format("Y-m-d"),Date::StartOffsetInConfirmReportPeriodForManager,Date::EndOffsetInConfirmReportPeriodForManager)
        :[]; //後で設定

        // データを[スタッフid=>[スタッフ名とデータ=>日付=>dateInViewとdata=>[all_main_project_names,]で表示]
        $all_data=GetOverviewByDay::get_overview_data($staff_ids,$date_sets);

        //
        return Inertia::render("BranchManager/ReportManagement/StaffToDate/DecideDate",[
            "what"=>"営業所担当",
            "type"=>"報告書決定",
            // ユーザー名
            "userName"=> BranchManagerList::where("id",Auth::user()->authable_id)->value("user_name"),
            "allData"=>$all_data
        ]);

    }


    // 報告書確認において、スタッフの決定後、日付が決定したとき
    public function decide_date_for_report_choice_post(ReportChoiceDecideRequest $request){
        // パラメータの取得
        [$date,$staff]=[$request->date,$request->staffId];

        // スタッフがその日に割り当てられた&配布したデータの取得
        // recordだけだと記入し忘れorしていない町目が拾われないため必ず必要
        [$assigns_with_records]=GetDataInStaffAndDate::get_assigned_and_recorderd_data($date,$staff);


        dd("Inertia直前！");

        return Inertia::render("",[
            "assigns"=>$assigns_with_records,
        ]);

    }




    // 報告書の確認or代替記入(日付から)
    public function choice_report_date_target()
    {
            // 日付=>[complete=>[id=>名前],only_plan=>[id=>名前],only_report[id=>名前]]で取得
            $date_staff_calendar="";

            return Inertia::render("BranchManager/ReportManagement/ChoiceFromStaff",[
            "what"=>"営業所担当",
            "type"=>"報告書確認",
            "dateStaffCalendar"=>$date_staff_calendar
           ]);
    }

   // 報告書の確認or代替記入(日付決定後、スタッフの選択)
    public function decide_staff_for_report_choice_post(ReportChoiceDecideRequest $request)
    {
        //
    }



    // 報告書の確認or代替記入(日付決定の投稿)
    public function choice_report_date_target_post(Request $request)
    {
        // スタッフリストの選択

        return Inertia::render("");
    }





    // 報告書の代替記入
    public function complete_report()
    {
        //
    }


    // 報告書の代替記入(最終決定)
    public function complete_report_post(Request $request)
    {
        //
    }


    // 報告書の編集(選択)
    public function choice_edit_report_target()
    {
        //
    }

}
