<?php

// DistributionAssignmtntのヘルパー
namespace App\Support\Common\ModelHelpers;

use App\Models\DistributionAssignment;

class DistributionAssignmentHelpers{

    // 複数の該当スタッフにおけるassignはされたがsubmitされていないデータ(提出された日はすでに取得済み)
    public static function get_not_submitted_data_from_plural_staff_ids($staff_ids,$submitted_data_in_the_staffs_and_dates){
        // staff_idとdateのセットが提出済みのものを削除
        return DistributionAssignment::whereIn("staff_id", $staff_ids)->whereNot(function($query)use($submitted_data_in_the_staffs_and_dates){
            // staff_idとdateのセットはsubmitted_data_in_the_staffs_and_datesから捕捉
            foreach($submitted_data_in_the_staffs_and_dates as $submitted_data){
                // 上記のデータをforeachで回し、削除するデータを積み重ねていく(orWhereで積み重ねる)
                $query->orWhere(function($inner_query)use($submitted_data){
                $inner_query->where("staff_id",$submitted_data->staff_id)->where("date",$submitted_data->distribution_data);
                });
            }
        });
    }
}
