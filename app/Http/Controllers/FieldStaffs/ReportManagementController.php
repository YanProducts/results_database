<?php

namespace App\Http\Controllers\FieldStaffs;

use App\Actions\BranchManager\Report\GetOverviewByDayInStaffToDate;
use App\Actions\BranchManager\Report\StoreEditedReports;
use App\Actions\Shared\GetDataInStaffAndDate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\Date;
use App\Exceptions\BusinessException;
use App\Http\Requests\BranchManager\CompleteEditReportRequest;
use App\Models\FieldStaffList;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
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
        $all_data=GetOverviewByDayInStaffToDate::get_overview_data($auth->authable_id,$date_sets);

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


    // 編集の画面
    public function edit_report($date){

        //その案件が既に入力担当にダウンロードされていたら編集できない(もしくは営業所担当が確認し終えたら？)
        if(!"condition"){
            return back()->withErrors(["alreadyChecked"=>"担当が確認をし終えています\n訂正の場合は営業所の担当にお伝えください"]);
        }

        // 本日の報告書以外は変更させない
        // 後でコメントアウト消し、実装つけること！実験は成功してる！
        if($date!==Carbon::now()->format("Y-m-d")){
            return back()->withErrors(["dateLimit"=>"本日より前のデータは変更できません"]);
        }


        // そのスタッフに割り当てられた案件と、それに即した結果データを返す
        return Inertia::render("BranchManager/ReportManagement/EditReport",[
            "what"=>"現場担当",
            "type"=>"報告書編集",
            "staff"=>$staff=Auth::user()->authable_id,
            "dateSet"=>DateHelper::change_key_value_set($date),
            "assignWithRecords"=>GetDataInStaffAndDate::get_assigned_data($staff,[$date=>""],true,DistributionRecordHelpers::data_in_the_date_and_staff($date,$staff))[0],
        ]);
    }

    // 実際の編集の投稿
    // 検証は営業所担当のものを借りてくる(営業所特有の機能もあるため、全体としてshareには移さず、1部を借りてくる)
    public function edit_report_post(CompleteEditReportRequest $request){
     try{
        // staffは自分のIdと同じ(requestでバリデーションは終了)
         StoreEditedReports::store_edited_reports($request->date,$request->reportData,$request->staff);
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            throw new BusinessException("データ挿入時のエラーです");
        }

        return redirect()->route("view_information")->with(["information_message"=>"編集完了しました","linkRouteName"=>"field_staff.overview_reports","linkPageInJpn"=>"確認ページ"]);

    }


}
