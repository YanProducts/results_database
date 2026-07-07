<?php

namespace App\Http\Controllers\FieldStaffs;

use App\Actions\FieldStaff\ReportManagement\GetOverviewByDay;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\Date;
use App\Models\FieldStaffList;
use App\Utils\DateHelper;
use App\Utils\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

//スタッフから報告書の確認や編集のコントローラー
class ReportManagementController extends Controller
{

    // 過去提出した報告書の確認(トップ)
    public function overview_reports($year=null,$month=null){

        // ログインユーザーのid
        $staff_id=Auth::user()->authable_id;

        // 日付の範囲のセットを取得
        $date_sets=
        empty($year) || empty($month) ?
        DateHelper::get_date_key_value_sets_for_view(Carbon::now()->format("Y-m-d"),Date::StartOffsetInConfirmReportPeriod,Date::EndOffsetInConfirmReportPeriod)
        :[]; //後で設定


        // そのスタッフに割り当てられた全データの概要(1月前から)
         // 日付→[配布済の有無・メイン案件名セット・主な市町村セット]
        $all_data=GetOverviewByDay::get_overview_data($staff_id,$date_sets);

        return Inertia::render("FieldStaff/ReportManagement/ReportOverview",[
                "prefix"=>"field_staff",
                "what"=>"現場担当",
                "type"=>"報告書入力",
                // スタッフのユーザー名
                "staff"=>FieldStaffList::where("id",$staff_id)->value("user_name"),
                "allData"=>$all_data
        ]);
    }


    // 指定日の報告書の確認や編集に向かう画面
    public function show_detail_report(Request $date){
        Log::info($date);
        dd(1);
    }

    // 編集の画面
    public function edit_report($date){

    }

    // 実際の編集の投稿
    public function edit_report_post(){

    }


}
