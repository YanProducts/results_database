<?php

// 配布予定と結果に共通するモデルのヘルパー関数

namespace App\Support\CommonModelHelpers;

use App\Models\DistributionPlan;
use App\Support\CommonModelHelpers\AddressHelpers;

//
class DistributionRecordHelpers{
    //  すでにデータが存在しているかの確認(町目分割の可能性updateはしない)
    public static function data_is_exists($project_id,$address_id){
        return
            DistributionPlan::where([
                ["project_id",$project_id],
                ["address_id",$address_id]
            ])->exists();
    }

    // プロジェクトと住所のidから、テーブルのidを返す
    public static function get_id_from_project_and_address($project_id,$address_id){
        return
            DistributionPlan::where([
                ["project_id",$project_id],
                ["address_id",$address_id]
            ])->value("id");
    }
}
