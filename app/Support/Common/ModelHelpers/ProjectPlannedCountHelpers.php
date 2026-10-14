<?php

namespace App\Support\Common\ModelHelpers;

use App\Constants\Date;
use App\Models\ProjectPlannedCount;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class ProjectPlannedCountHelpers{

    // 開始日が指定月以内(指定がなければDateに指定された期間)の設定部数などの予定
    public static function get_plans_by_start_day($start_month=null){
        //いつからのデータをとってくるか(デフォルトは1ヶ月前)
        $start_date=Carbon::now()->subMonths($start_month ?? Date::ProjectOverviewStartDateOffsetMonth);
        // この時点ではgropuByしない。formatは別途行う
        return  ProjectPlannedCount::select("id","start_date","place_id","project_id","round_number","end_date","main_id","counts")->where("start_date",">",$start_date)->get();
    }

    // そのプロジェクトidにおける合計想定部数の算出
    public static function  get_total_planned_counts_by_the_project($project_id){
       return ProjectPlannedCount::where("project_id",$project_id)->pluck("counts")->sum();
    }

    // 指定された日から日に含まれるIdと表示用案件名(案件IdとroundNumberのセット)を返す
    // place_idがあれば、営業所限定した案件を返す
    public static function get_id_and_project_names_for_view_in_date_array($start_offset,$end_offset,$place_id=null){
        // 現在日時
        $now=CarbonImmutable::now();

        // 開始日と終了日
        $start_date=$now->addDays($start_offset);
        $end_date=$now->addDays($end_offset);

        //終了日がSqlでnullのものを考慮しつつ、開始日~終了日の間が案件期日のものを返す
        // place_idが指定されている場合と指定されていない場合も考慮
        return ProjectPlannedCount::select("id","start_date","end_date","project_name","round_number")->where(function($query1)use($start_date,$end_date){return (($query1->whereBetween("start_date",[$start_date,$end_date])->andWhere("end_date",null)) || ($query1->where("start_date","<=",$start_date)->andWhere("end_date","<>",null)->andWhere("end_date",">=",$end_date)));})
        ->andWhere(fn($query2) => empty($place_id) ? $query2 :$query2->where("place_id",$place_id));
    }


}
