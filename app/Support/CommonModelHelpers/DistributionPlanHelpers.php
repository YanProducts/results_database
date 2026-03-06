<?php

// 配布予定のモデルのヘルパー関数

namespace App\Support\CommonModelHelpers;

use App\Models\DistributionPlan;

class DistributionPlanHelpers{
    //  すでにデータが存在しているかの確認(町目分割の可能性updateはしない)
    public static function data_is_exists($projectId,$start_date,$end_date,$addressesId){
        DistributionPlan::where([
            ["projectId",$projectId],
        ]);
    }
}
