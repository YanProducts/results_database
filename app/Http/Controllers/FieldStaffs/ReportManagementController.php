<?php

namespace App\Http\Controllers\FieldStaffs;

use App\Actions\BranchManager\Report\GetOverviewByDay;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\Date;
use App\Exceptions\BusinessException;
use App\Models\BranchManagerList;
use App\Models\FieldStaffList;
use App\Support\Auth\UserRoleResolver;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
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
    // このルートは営業所担当と共通して使う
    public function overview_reports($year=null,$month=null){

        $auth=Auth::user();

        // 日付の範囲のセットを取得
        $date_sets=
        (empty($year) || empty($month)) ?
        DateHelper::get_date_key_value_sets_for_view(Carbon::now()->format("Y-m-d"),Date::StartOffsetInConfirmReportPeriod,Date::EndOffsetInConfirmReportPeriod)
        :[]; //後で設定


        // そのスタッフに割り当てられた全データの概要(dateで設定された範囲内)
        // 日付→[配布済の有無・メイン案件名セット・主な市町村セット]
        // この部分はBranchManagerよりもらってくる。roleによって取得するデータの変化
        $all_data=GetOverviewByDay::get_overview_data($auth->authable_id,$date_sets);


        // モデルクラスの取得
        return Inertia::render("FieldStaff/ReportManagement/ReportOverview",[
                "prefix"=>"field_staff",
                "what"=>"現場担当",
                "type"=>"報告書入力",
                // ユーザー名
                "userName"=> FieldStaffList::where("id",$auth->authable_id)->value("user_name"),
                "allData"=>$all_data
        ]);
    }

    // 報告書確認において、スタッフの決定後、日付が決定したとき
    public function decide_date_for_report_choice(){

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
