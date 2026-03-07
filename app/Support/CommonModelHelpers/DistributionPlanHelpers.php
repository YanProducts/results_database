<?php

// 配布予定のモデルのヘルパー関数

namespace App\Support\CommonModelHelpers;

use App\Models\DistributionPlan;

class DistributionPlanHelpers{
    //  すでにデータが存在しているかの確認(町目分割の可能性updateはしない)
    public static function data_is_exists($project_id,$start_date,$end_date,$address_id){
        DistributionPlan::where([
            ["project_id",$project_id],
        ]);
    }
}
