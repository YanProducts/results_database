<?php

namespace App\Support\Common\ModelHelpers;

use App\Constants\Date;
use App\Models\ProjectPlannedCount;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class ProjectPlannedCountHelpers{

    // その案件Id&その営業所での案件が何度か(すなわちround_number)を返す(これに+1したものが、登録時のround_number)
    // 併配の場合はnullであり、maxはnullはnullと返してくれる
    public static function get_max_round_number_in_the_project_and_place($project_id,$place_id){
        return ProjectPlannedCount::where("place_id",$place_id)->where("project_id",$project_id)->max("round_number");
    }



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
    // need_mainがあれば、メイン案件のみを返す
    public static function get_id_and_project_names_for_view_in_date_array($start_offset,$end_offset,$place_id=null,$need_main=false){
        // 現在日時
        $now=CarbonImmutable::now();

        // 開始日と終了日
        $start_date=$now->addDays($start_offset);
        $end_date=$now->addDays($end_offset);

        //終了日がSqlでnullのものを考慮しつつ、開始日~終了日の間が案件期日のものを返す
        // place_idが指定されている場合と指定されていない場合も考慮
        return ProjectPlannedCount::select("id","start_date","end_date","project_id","round_number")->where(function($query1)use($start_date,$end_date){ return(($query1
            ->where(function($query1_1)use($start_date,$end_date){return $query1_1->whereBetween("start_date",[$start_date,$end_date])->where("end_date",null);})
            ->orWhere(function($query1_2)use($start_date,$end_date){return $query1_2->where("start_date","<=",$end_date)->where("end_date",">=",$start_date);})
            ));
        })
            ->when(!empty($place_id) , fn($query2)=>$query2->where("place_id",$place_id))
            ->when(!empty($need_main) , fn($query3)=>$query3->where("main_id",null))
        ->get();
    }

    // その日、その営業所に来ている、project_planned_countのidを返す(現状、使用してない)
    public static function get_id_by_the_place_and_date($place_id,$date){
        return
        ProjectPlannedCount::where("place_id",$place_id)->where(fn($query)=>$query->where(fn($query1)=>$query1->where("start_date",$date)->whereNull("end_date"))->orWhere(fn($query2)=>$query2->where("end_date",">=",$date)->where("start_date","<=",$date)))->pluck("id");
    }

    // project_planned_countsのテーブルにおけるidから、project_idとround_numberを返す(主にdistribution_plansを逆引きするのに使用)
    public static function get_project_id_and_round_number_and_place_id_for_search_plans($project_planned_ids,$place_id){
        // findは配列やコレクションで複数指定可能、第二引数はselectで限定するカラム（だが今回は最終的にsortでも使用するのでdateも取得したいので、そのままfindで取得）
        return ProjectPlannedCount::find($project_planned_ids)->where("place_id",$place_id)->pluck("project_id","round_number");
    }



}
