<?php

namespace App\Http\Controllers\BranchManager;

use App\Actions\BranchManager\Confirm\GetSqlData;
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
                "allTownSets"=>$address_lists
            ]);
    }

    // 何を確認するかの決定後の投稿
    public function confirm_project_record_post(ProjectRecordRequest $request){

    }

}
