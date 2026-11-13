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

        // nowならデータを入れた時刻より前のものは弾いてしまう
        // 00:00:00でtodayは挿入
        $date=CarbonImmutable::today();

         return
        //  00:00:00ではじめているので、endOfDay()を入れておかないと
         DistributionProjectAssignment::whereBetween("date",[$date->addDays($start_offset),$date->addDays($end_offset)->endOfDay()])->whereIn("staff_id", FieldStaffListHelpers::get_all_staffs_in_the_place($place_id))->pluck("date")->unique();

    }

    // 指定された期間内におけるAssignされたproject_plannedのid(最終的にはprojectのidとround_numberも取得に繋げる)とスタッフIdで取得[日付でgroupBy]（*ここは通常盤のassignとも最終的に連結する）
    // staffIdは既に取得済み
    public static function get_assigns_between_the_date($staff_ids,$start_date,$end_date){
        return DistributionProjectAssignment::select("project_plan_id","staff_id","date")->whereBetween("date",[$start_date,$end_date])->whereIn("staff_id",$staff_ids)->get()->groupBy("date");
    }

    // 特定のスタッフ、特定の期間のコレクションの取得
    public static function get_assigns_by_staff_and_date($staff_id,$date_sets){
        return DistributionProjectAssignment::whereIn("date",$date_sets)->where("staff_id",$staff_id)->get();
    }


    // 指定された日、指定された営業所における、Assignの削除()
    public static function delete_assign_in_the_date_and_plan_ids($date,$place_id){

        // 既にトランザクションの内部にいる前提
        // end_dateを指定していない前提(そもそもend_dateが必要かも難しい)
        DistributionProjectAssignment::where("date",$date)->whereIn("staff_id",FieldStaffListHelpers::get_all_staffs_in_the_place($place_id))->delete();
    }

    // 案件で振った際に、該当スタッフはprojet_plan_idを振られた予定を持っているか
    public static function is_project_plan_id_by_the_staff_and_date_exists($staff_id,$date,$project_plan_id){
        return DistributionProjectAssignment::where("staff_id",$staff_id)->where("project_plan_id",$project_plan_id)->where(function($query)use($date){
            $query->where("date",$date)->orWhere(function($inner_query)use($date){
                $inner_query->where("date","<=",$date)->where("end_date",">=",$date);
            });
        })
        ->exists();
    }

    // project_idとround_numberとplace_idのセットから一意に決まったproject_planned_idを返す
    //存在しなければ内部でエラーを投げる


}
