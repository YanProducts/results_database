<?php

// DistributionAssignmtntのヘルパー
namespace App\Support\Common\ModelHelpers;

use App\Exceptions\BusinessException;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DistributionProjectAssignment;

class DistributionProjectAssignmentHelpers{
    // 該当スタッフ、該当日における割当済みの案件


    // 該当スタッフ、該当期間における、未提出以外のデータ(status=1かどうかで行うと2日案件で2日に分けて行く予定の場合が含まれなくなるので、該当日該当スタッフのデータがあるかどうかで検証)


    // 該当営業所(authから取得)における、期間内における割り当てデータが少しでもある日時を配列で返す
    public static function get_date_sets_having_assign_data($start_offset,$end_offset,$place_id){

        $date=CarbonImmutable::now();

         return  DistributionProjectAssignment::whereBetween("date",[$date->addDays($start_offset),$date->addDays($end_offset)])->whereIn("staff_id", FieldStaffListHelpers::get_all_staffs_in_the_place($place_id))->pluck("date")->unique();

    }

    // 指定された期間内におけるAssignされたproject_plannedのid(最終的にはprojectのidとround_numberも取得に繋げる)とスタッフIdで取得[日付でgroupBy]（*ここは通常盤のassignとも最終的に連結する）
    // staffIdは既に取得済み
    public static function get_assigns_between_the_date($staff_ids,$start_date,$end_date){
        return DistributionProjectAssignment::select("project_plan_id","staff_id","date")->whereBetween("date",[$start_date,$end_date])->whereIn("staff_id",$staff_ids)->get()->groupBy("date");
    }





    // // 該当のplanに対応するassignの取得
    // public static function get_assigns_by_plan_ids($plan_ids){
    //     return DistributionProjectAssignment::select("plan_id","staff_id","date")->whereIn("plan_id",$plan_ids)->get()->groupBy("plan_id");
    // }


    // // その日の複数スタッフに割り当てられている案件を[スタッフid=>planidセット]で変換
    // public static function get_staff_and_plan_ids_in_the_date($date,$staff_ids){

    //     return (DistributionProjectAssignment::select("staff_id","plan_id")->whereIn("staff_id",$staff_ids)
    //     ->where(function($outer_query)use($date){
    //     $outer_query->where(function($query)use($date){$query->where("date","<=",$date)->where("end_date",">=",$date);})
    //     ->orWhere(function($query)use($date){$query->where("date",$date)->whereNull("end_date");});
    //     })
    //     ->get()->groupBy("staff_id"))->mapWithKeys(fn($each_data,$staff_id)=>[$staff_id=>$each_data->pluck("plan_id")]);
    // }

    // // 複数の該当スタッフにおけるassignはされたがsubmitされていないデータ(提出された日はすでに取得済み) date_setsを指定すると「指定された期間」のものになる。
    // public static function get_not_submitted_data_from_plural_staff_ids($staff_ids,$submitted_data_in_the_staffs_and_dates,$date_sets=null){

    //     // staff_idとdateのセットが提出済みのものを削除
    //     return DistributionProjectAssignment::whereIn("staff_id", $staff_ids)
    //     ->where(function($query)use($date_sets){
    //         if(!empty($date_sets)){
    //             $query->whereIn("date",array_keys($date_sets));
    //         }
    //     })
    //     ->whereNot(function($query)use($submitted_data_in_the_staffs_and_dates){
    //         // staff_idとdateのセットはsubmitted_data_in_the_staffs_and_datesから捕捉
    //         foreach($submitted_data_in_the_staffs_and_dates as $submitted_data){
    //             // 上記のデータをforeachで回し、削除するデータを積み重ねていく(orWhereで積み重ねる)
    //             $query->orWhere(function($inner_query)use($submitted_data){
    //             $inner_query->where("staff_id",$submitted_data->staff_id)->where("date",$submitted_data->distribution_data);
    //             });
    //         }
    //     });
    //     }

        // 指定された日、指定されたplan_idリストにおける、Assignの削除()
        //複数日のものをどう反映させるか？現状はend_dateは使用していない。最後まで現状の形式のままでいくのも1つ
        public static function delete_assign_in_the_date_and_plan_ids($date,$plan_ids){
            try{
                DB::transaction(function()use($date,$plan_ids){
                    DistributionProjectAssignment::where("date",$date)->whereIn("plan_id",$plan_ids)->delete();
                });
            }catch(\Throwable $e){
                Log::info($e->getMessage());
                throw new BusinessException("データ削除の際にエラーが生じしました");
            }
        }

}
