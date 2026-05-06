<?php

// 報告書で上がってきたデータをSQL登録する
namespace App\Actions\FieldStaff;

use App\Models\DistributionAssignment;
use App\Models\DistributionPlan;
use App\Models\DistributionRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreAfterDistribution{

    // データ挿入の流れ
    public static function store_report_data_procedure($request){
        $date=$request->date();
        $report_data=$request->reportData;

        // 投稿されたassignId(必然的にmainになる)
        $assign_ids=collect($report_data)->map(fn($each_report_data)=>$each_report_data->assignId)->toArray();

        // n+1防止のためsqlデータを取得
        [$main_plan_ids_key_by_assigned_id,$main_data_for_insert,$sub_data_for_insert]=self::get_sql_data($assign_ids,$report_data);

        //投稿データとSQLデータから挿入配列を取得
        $insert_array=self::get_report_data_for_insert($date,$report_data,$main_plan_ids_key_by_assigned_id,$main_data_for_insert,$sub_data_for_insert);

        DB::transaction(function()use($insert_array,$assign_ids){
            // 挿入
            DB::table("distribution_records")->insert([$insert_array]);

            // assignmentのstatusの変更
            DistributionAssignment::whereIn("id",$assign_ids)->update(["status"=>1]);
        });

    }

     // n+1のためにSQLデータを先に取得
     public static function get_sql_data($assign_ids,$report_data){

            // n+1防止のため、assignIdに対応するplan_idのデータを取得(そのstaffが対象かどうかはバリデーション済)=(必然的にmain案件)
            $main_plan_ids_key_by_assigned_id=DistributionAssignment::whereIn("id",$assign_ids)->pluck("plan_id","id");

            //n+1防止のため、メイン案件のplan_idに対応するaddress_id,project_idを先に取得
            $main_data_for_insert=self::get_main_data_for_insert_from_plan_id($main_plan_ids_key_by_assigned_id);

            //n+1防止のため、サブ案件のmain_idに対応するplan_id,address_id,project_idを先に取得
            $sub_data_for_insert=self::get_sub_data_for_insert_from_assign_id($main_plan_ids_key_by_assigned_id);

            return[$main_plan_ids_key_by_assigned_id,$main_data_for_insert,$sub_data_for_insert];
     }

     //メイン案件
     public static function get_main_data_for_insert_from_plan_id($main_plan_ids){
        // [project_id,address_id]がplan_idをキーにして入れ子の配列になったもの
         return DistributionPlan::select("id as plan_id","address_id","project_id")->whereIn("id",$main_plan_ids)->get()->keyBy("plan_id");
    }

     //併配案件
     public static function get_sub_data_for_insert_from_assign_id($main_plan_ids){
        // [project_id,address_id]がmainのplan_idをキーにして入れ子の配列になったもの
         return DistributionPlan::select("id as plan_id","main_id","address_id","project_id")->WhereIn("main_id",$main_plan_ids)->get()->keyBy("main_id");
    }

     // reportデータの個々の配列の中身
    public static function get_report_data_for_insert($date,$report_data,$main_plan_ids_key_by_assigned_id,$main_data_for_insert,$sub_data_for_insert){
            // 現在の日時
            $now=Carbon::now();
            // どのデータでも同じデータ
            $base_insert_array[]=[
                "distribution_date"=>$date,
                "staff_id"=>Auth::user()->authable_id,
                "created_at"=>$now, "updated_at"=>$now,
            ];
            // report_dataは必ず１以上の配列とバリデーション済
            foreach($report_data as $each_report_data){
                // 投稿されたassignId
                $assign_id=$each_report_data->assignId;
                // そこに対応するplan_id
                $plan_id=$main_plan_ids_key_by_assigned_id[$assign_id];
                // 対応するplan_idのデータ(そのstaffが対象かどうかはバリデーション済)=(すでにmain案件)
                $insert_array[]=[
                    ...$base_insert_array,
                    "distribution_count"=>$each_report_data->mainCount,
                    "address_id"=>$main_data_for_insert[$plan_id][0]["address_id"],
                    "project_id"=>$main_data_for_insert[$plan_id][0]["project_id"],
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
                $sub_project_id=$reported_sub_data["project_id"];

                // サブ案件のデータをメイン案件のプランidのキーに対して取得し、そのうちに投稿されたproject_idと同じものを取得
                $sub_plan_data=$sub_data_for_insert[$plan_id]->filter(fn($sub_data)=>$sub_data->project_id==$sub_project_id);

                // 対応するplan_idのデータ
                $insert_array[]=[
                    ...$base_insert_array,
                    "distribution_count"=>$each_report_data->mainCount,
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
