<?php

// DistributionAssignmtntのヘルパー
namespace App\Support\Common\ModelHelpers;

use App\Models\DistributionAssignment;
use Illuminate\Support\Facades\Log;

class DistributionAssignmentHelpers{
    // 該当スタッフ、該当日における割当済みの案件


    // 該当スタッフ、該当期間における、未提出以外のデータ(status=1かどうかで行うと2日案件で2日に分けて行く予定の場合が含まれなくなるので、該当日該当スタッフのデータがあるかどうかで検証)



    // その日の複数スタッフに割り当てられている案件を[スタッフid=>planidセット]で変換
    public static function get_staff_and_plan_ids_in_the_date($date,$staff_ids){

        return (DistributionAssignment::select("staff_id","plan_id")->whereIn("staff_id",$staff_ids)
        ->where(function($outer_query)use($date){
        $outer_query->where(function($query)use($date){$query->where("date","<=",$date)->where("end_date",">=",$date);})
        ->orWhere(function($query)use($date){$query->where("date",$date)->whereNull("end_date");});
        })
        ->get()->groupBy("staff_id"))->mapWithKeys(fn($each_data,$staff_id)=>[$staff_id=>$each_data->pluck("plan_id")]);
    }

    // 複数の該当スタッフにおけるassignはされたがsubmitされていないデータ(提出された日はすでに取得済み) date_setsを指定すると「指定された期間」のものになる。
    public static function get_not_submitted_data_from_plural_staff_ids($staff_ids,$submitted_data_in_the_staffs_and_dates,$date_sets=null){

        // staff_idとdateのセットが提出済みのものを削除
        return DistributionAssignment::whereIn("staff_id", $staff_ids)
        ->where(function($query)use($date_sets){
            if(!empty($date_sets)){
                $query->whereIn("date",array_keys($date_sets));
            }
        })
        ->whereNot(function($query)use($submitted_data_in_the_staffs_and_dates){
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
