<?php

// 配布予定と結果に共通するモデルのヘルパー関数

namespace App\Support\CommonModelHelpers;

use App\Exceptions\BusinessException;
use App\Models\DistributionPlan;
use App\Support\CommonModelHelpers\AddressHelpers;

//
class DistributionPlanHelpers{
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

    // 該当営業所の該当機関に来ている案件を返す
    public static function get_plan_in_the_place_and_period($place_id,$date_sets){

        // dateが存在しないとき (issetは複数キーの同時チェック可能)、もしくはdateがCarbonではない時
        if (!isset($date_sets['start'], $date_sets['end'])) {
            throw new BusinessException("日付取得時のエラーです");
        }


        $plan_in_the_place_and_period=DistributionPlan::where("place_id",$place_id)->where("start_date","<=",$date_sets["start"])
        ->where("end_date",">=",$date_sets["end"])
        ->get();

        return $plan_in_the_place_and_period;
    }

}
