<?php

// DistributionAssignmtntのヘルパー
namespace App\Support\Common\ModelHelpers;

use App\Models\DistributionAssignment;

class DistributionAssignmentHelpers{
    // 該当スタッフにおけるassignはされたがsubmitされていないデータ(提出された日はすでに取得済み)
    public static function get_not_submitted_data($staff_id,$submitted_days){
        return DistributionAssignment::where("staff_id",$staff_id)->whereNotIn("date",$submitted_days);
    }
}
