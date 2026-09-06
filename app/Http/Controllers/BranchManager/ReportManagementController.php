<?php

namespace App\Http\Controllers\BranchManager;

use App\Actions\BranchManager\Report\DateToStaff\GetOverviewForChoiceDate;
use App\Actions\BranchManager\Report\DateToStaff\GetOverviewForChoiceStaff;
use App\Actions\Shared\GetDataInStaffAndDate;
use App\Actions\BranchManager\Report\GetOverviewByDayInStaffToDate;
use App\Actions\BranchManager\Report\StoreEditedReports;
use App\Constants\Date;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchManager\ChoiceFromDateRequest;
use App\Http\Requests\BranchManager\ChoiceFromStaffRequest;
use App\Http\Requests\BranchManager\CompleteEditReportRequest;
use App\Http\Requests\BranchManager\ReportChoiceDecideRequest;
use App\Models\BranchManagerList;
use App\Models\DistributionRecord;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Utils\DateHelper;
use App\Utils\Session;
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
        // 前回バリデーション返しに備えた繊維の際に入れておいたセッションを消す
        Session::delete_sessions(["staff_for_edit_report","date_for_edit_report"]);

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
        $all_data=GetOverviewByDayInStaffToDate::get_overview_data($staff_ids,$date_sets);

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
    public function decide_all_for_report_choice_post(ReportChoiceDecideRequest $request){
        // パラメータの取得
        [$date,$staff]=[$request->date,$request->staffId];

        // バリデーション戻りのことを備えてsession保存(その後に完全遷移)
        Session::create_sessions([
            "staff_for_edit_report"=>$staff,
            "date_for_edit_report"=>$date,
        ]);

        // バリデーション返しに備えて１度リダイレクト
        // フラッシュセッションに入れて引数は渡す
        return redirect()->route("branch_manager.edit_report_view");

    }

    //報告書編集そのものview(バリデーション返しに備えて1つリダイレクトを入れている)
    public function edit_report_view(){

        // そのスタッフに割り当てられた案件と、それに即した結果データを返す
        return Inertia::render("BranchManager/ReportManagement/EditReport",[
            "what"=>"営業所担当",
            "type"=>"報告書編集",
            "staff"=>$staff=session("staff_for_edit_report"),
            "dateSet"=>$date_set=DateHelper::change_key_value_set($date=session("date_for_edit_report")),
            "assignWithRecords"=>GetDataInStaffAndDate::get_assigned_data($staff,$date_set,true,DistributionRecordHelpers::data_in_the_date_and_staff($date,$staff))[0],
        ]);
    }

    // 報告書の確認or代替記入(日付から)
    public function choice_report_date_target()
    {
            // 前回バリデーション返しに備えた繊維の際に入れておいたセッションを消す
            Session::delete_sessions(["staff_for_edit_report","date_for_edit_report"]);

            // 日付=>[cityLists=>その時に行った市の名前(検索しやすいように市で取得)、complete=>[id=>名前],only_plan=>[id=>名前],only_report[id=>名前]]で取得
            $date_staff_calendar=GetOverviewForChoiceDate::get_staff_status_and_city_names_in_each_day();

            return Inertia::render("BranchManager/ReportManagement/DateToStaff/ChoiceFromDate",[
            "what"=>"営業所担当",
            "type"=>"報告書確認",
            "dateStaffCalendar"=>$date_staff_calendar
           ]);
    }


    // 報告書の確認or代替記入(日付決定の投稿)
    public function choice_report_date_target_post(ChoiceFromDateRequest $request)
    {
        $date=$request->date;

        // その日付に来ているスタッフを[id,staff_name,status=報告書提出状況、towns=スタッフが行く町目いくつか、projects=スタッフに割り当てられたメイン案件]で選択
        $staffs_informations_in_the_date=GetOverviewForChoiceStaff::get_staffs_information_in_the_day($date);

        return Inertia::render("BranchManager/ReportManagement/DateToStaff/DecideStaff",[
            "what"=>"営業所担当",
            "type"=>"報告書確認",
            "date"=>$request->date,
            "staffsInformationsInTheDate"=>$staffs_informations_in_the_date->toArray()
        ]);
    }


    // 報告書の確認or代替記入(日付決定後、スタッフの選択)
    public function decide_staff_for_report_choice_post(ReportChoiceDecideRequest $request)
    {
        //
    }




    // 報告書の代替記入(最終決定)
    public function complete_report_post(CompleteEditReportRequest $request)
    {

        try{
            StoreEditedReports::store_edited_reports($request->date,$request->reportData,$request->staff);
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            throw new BusinessException("データ挿入時のエラーです");
        }

        // バリデーション返しに備えた繊維の際に入れておいたセッションを消す
        Session::delete_sessions(["staff_for_edit_report","date_for_edit_report"]);

        return redirect()->route("view_information")->with(["information_message"=>"編集完了しました","linkRouteName"=>"branch_manager.top_page","linkPageInJpn"=>"営業所トップ"]);

    }


    // 報告書の編集(選択)
    public function choice_edit_report_target()
    {
        //
    }

}
