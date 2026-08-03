<?php

namespace App\Http\Controllers\BranchManager;

use App\Actions\BranchManager\Confirm\GetSqlData;
use App\Actions\BranchManager\Confirm\Params;
use App\Constants\Date;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchManager\ProjectRecordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

// 営業所担当が、町丁目データを確認するときのコントローラー
class ProjectRecordController extends Controller
{
    //選ぶデータの確認(町丁目、期限、スタッフ(1：全体、２：営業所内、３：特定スタッフ))
    public function confirm_project_record(){

        // スタッフリストは自分の営業所における[id,名前]のリスト
        // 住所リストは県→市→町の入れ子の配列
        [$staff_lists,$address_lists]=GetSqlData::get_reference_sql_data();

        return Inertia::render("BranchManager/StoredDataCheck/ConfirmTownRecord",[
                "prefix"=>"branch_manager",
                "what"=>"営業所担当",
                "type"=>"町丁目データの確認",
                "staffLists"=>$staff_lists,
                "allTownSets"=>$address_lists,
                "startOffset"=>Date::ResultSerachBeforeYearLimit
            ]);
    }

    // 何を確認するかの決定後の投稿
    public function confirm_project_record_post(ProjectRecordRequest $request){

    // パラメータ捕捉しクラス挿入
    $params=new Params(
        staff_ids:$request->staffIds,
        start_year:$request->startYear,
        end_year:$request->endYear,
        pattern:$request->pattern,
        pref:$request->prefName,
        city:$request->cityName,
        address_id:$request->addressId,
        address_names:$request->addressNames
     );

    //  sqlデータ取得
    $sql_data=GetSqlData::get_filtered_sql_data($params);
    // Log::info($sql_data);

    // UI用の言葉に変換(連想配列で入れる)
    ["staff_names"=>$staff_names,"date_range"=>$date_range]=$params->get_string_for_UI();

    // allDataは[各町目id=>町目名、平均・最大・中央・具体的な数リスト]がスタッフごと、営業所ごと、全体で分かれて入っている
    return Inertia::render("BranchManager/StoredDataCheck/ViewTownRecord",[
            "prefix"=>"branch_manager",
            "what"=>"営業所担当",
            "type"=>"データ確認",
            "searchStaffs"=>$staff_names,
            "searchRange"=>$date_range,
            "allData"=>$sql_data
    ]);

   }
}
