<?php

namespace App\Actions\Shared;
use App\Models\DistributionAssignment;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// 報告書の投稿・編集の中で、どのroleから来ようとも共通した流れ
class PrepareForStoreDistributionRecord{

    // 保存に必要なデータ(配布済に変更するassignidと保存用配列)を取得
    public static function prepare_for_store($date,$report_data,$staff_id,$from_edit=false){
        // 投稿されたassignId(必然的にmainになる)
        $assign_ids=(collect($report_data)->map(fn($each_report_data)=>$each_report_data["assignId"]))->toArray();

        // n+1防止のためsqlデータを取得
        [$main_plan_ids_key_by_assigned_id,$main_data_for_insert,$sub_data_for_insert]=self::get_sql_data($assign_ids);

        //投稿データとSQLデータから挿入配列を取得
        $insert_array=self::get_report_data_for_insert($date,$staff_id,$report_data,$main_plan_ids_key_by_assigned_id,$main_data_for_insert,$sub_data_for_insert,$from_edit);

        return [$assign_ids,$insert_array];
    }

    // n+1のためにSQLデータを先に取得
     public static function get_sql_data($assign_ids){

            // n+1防止のため、assignIdに対応するplan_idのデータを取得(そのstaffが対象かどうかはバリデーション済)=(必然的にmain案件)
            $main_plan_ids_key_by_assigned_id=DistributionAssignment::whereIn("id",$assign_ids)->pluck("plan_id","id");

            //n+1防止のため、メイン案件のplan_idに対応するaddress_id,project_idを先に取得
            $main_data_for_insert=DistributionPlanHelpers::get_main_data_for_insert_from_plan_id($main_plan_ids_key_by_assigned_id);

            //n+1防止のため、サブ案件のmain_idに対応するplan_id,address_id,project_idを先に取得
            $sub_data_for_insert=DistributionPlanHelpers::get_sub_data_for_insert_from_assign_id($main_plan_ids_key_by_assigned_id);

            return[$main_plan_ids_key_by_assigned_id,$main_data_for_insert,$sub_data_for_insert];
     }

     // reportデータの個々の配列の中身
    public static function get_report_data_for_insert($date,$staff_id,$report_data,$main_plan_ids_key_by_assigned_id,$main_data_for_insert,$sub_data_for_insert,$from_edit){
            // 現在の日時
            $now=Carbon::now();
            // どのデータでも同じデータ
            $base_insert_array=[
                "distribution_date"=>$date,
                "staff_id"=>$staff_id,
                "created_at"=>$now, "updated_at"=>$now,
            ];

            // report_dataは必ず１以上の配列とバリデーション済
            foreach($report_data as $each_report_data){

                // editの場合、mainCountのデータがない場合は飛ばす
                if($from_edit && !array_key_exists("mainCount",$each_report_data)){
                    continue;
                }

                // 投稿されたassignId
                $assign_id=$each_report_data["assignId"];
                // そこに対応するplan_id
                $plan_id=$main_plan_ids_key_by_assigned_id[$assign_id];

                // 対応するplan_idのデータ(そのstaffが対象かどうかはバリデーション済)=(すでにmain案件)
                $insert_array[]=[
                    ...$base_insert_array,
                    // 初期投稿で併配のみの場合は空欄になっているので「0」を記入
                    "distribution_count"=>$each_report_data["mainCount"] ?? 0,
                    "address_id"=>$main_data_for_insert[$plan_id]["address_id"],
                    "project_id"=>$main_data_for_insert[$plan_id]["project_id"],
                    "remarks"=>"",
                    "plan_id"=>$plan_id
                ];
                // 投稿された併配案件
                $insert_array=self::get_sub_insert_arrays($insert_array,$each_report_data,$sub_data_for_insert,$plan_id,$base_insert_array);
            }

        // 配列は上記より必ず存在。またPHPはブロック内宣言でもいける
        return $insert_array;
    }

    //   サブ案件のデータを配列に入れる
    public static function get_sub_insert_arrays($insert_array,$each_report_data,$sub_data_for_insert,$plan_id,$base_insert_array){
            foreach($each_report_data["subData"] as $reported_sub_data){
                // reported_sub_dataはproject_idとsubCountが保存
                $sub_project_id=$reported_sub_data["projectId"];

                // サブ案件のデータをメイン案件のプランidのキーに対して取得し、そのうちに投稿されたproject_idと同じものを取得(filterは単一ではないので0を返すことが必要)
                $sub_plan_data=$sub_data_for_insert->get($plan_id)?->first(fn($sub_data)=>$sub_data->project_id==$sub_project_id) ?? throw new \App\Exceptions\BusinessException("メインに対応する併配案件データが見つかりません");

                // 途中で消して空白になっている場合は次のループへ
                if($reported_sub_data["subCount"]==""){
                    continue;
                }

                // 対応するplan_idのデータ
                $insert_array[]=[
                    ...$base_insert_array,
                    "distribution_count"=>$reported_sub_data["subCount"],
                    "project_id"=>$sub_project_id,
                    // サブ案件のプランId(メイン案件のplan_idとは別)
                    "plan_id"=>$sub_plan_data->plan_id,
                    // サブ案件のaddress_id
                    "address_id"=>$sub_plan_data->address_id,
                    "remarks"=>"",
                ];
            }
        return $insert_array;
    }
}
