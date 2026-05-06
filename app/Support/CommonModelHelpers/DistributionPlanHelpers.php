<?php

// 配布予定と結果に共通するモデルのヘルパー関数

namespace App\Support\CommonModelHelpers;

use App\Exceptions\BusinessException;
use App\Models\DistributionPlan;
use App\Support\CommonModelHelpers\AddressHelpers;
use Illuminate\Support\Facades\Log;

//
class DistributionPlanHelpers{
    //  すでにデータが存在しているかの確認(町目分割の可能性updateはしない)
    public static function data_is_exists($project_id,$address_id){
        return
            DistributionPlan::where([
                ["project_id",$project_id],
                ["address_id",$address_id]
            ])->exists();
    }

    // プロジェクトと住所のidから、テーブルのidを返す
    public static function get_id_from_project_and_address($project_id,$address_id){
        return
            DistributionPlan::where([
                ["project_id",$project_id],
                ["address_id",$address_id]
            ])->value("id");
    }

    // 該当営業所の該当機関に来ている案件を返す
    public static function get_plan_in_the_place_and_period($place_id,$date_sets){

    $start=$date_sets["start"] ?? throw new BusinessException("日付取得のエラーです");
    $end=$date_sets["end"] ?? throw new BusinessException("日付取得のエラーです");


        // 「表示する日付内の最初でまだ終わっていない」かつ「表示する日付の最後ですでに始まっている」ものを選択
        $plan_in_the_place_and_period=DistributionPlan::where("place_id",$place_id)->where("start_date","<=",$end)
        ->where("end_date",">=",$start)
        ->get();

        return $plan_in_the_place_and_period;
    }

    // その町目予定は割り当てなどで選択された日の中に入っているか(バリデーションなどで使用)、正否が返る
    public static function is_plan_id_within_the_date($date,$plan_ids){

        // N+1防止のため先にコレクションを取得(idの内部に入っているものを先に取得)
        // collectionがwhereで大小比較は難しくなるので、配列にしてarray_filterで大小比較
        $plan_in_sql=DistributionPlan::select("id","start_date","end_date")->whereIn("id",$plan_ids)->get();

        // 全てのコレクションのstartとendが期間内に入っているかを比較
        // everyは全てに置いて成り立つかのチェック
        return $plan_in_sql->every(fn($plan)=>$plan["start_date"]<=$date && $plan["end_date"]>=$date);

    }

     //メイン案件のidセットから、plan_idをキーに住所と案件のIDを取得＝すなわちメイン案件の情報をidごとに取得
     public static function get_main_data_for_insert_from_plan_id($main_plan_ids){
        // [project_id,address_id]がplan_idをキーにして配列になったもの(idがそのidなのは唯一に決まるのでkeyBy)
         return DistributionPlan::select("id as plan_id","address_id","project_id")->whereIn("id",$main_plan_ids)->get()->keyBy("plan_id");
    }

    //メイン案件のidセットから、main_idをキーにidと住所と案件のIDを取得＝すなわちメイン案件に紐づく併配データを取得
     public static function get_sub_data_for_insert_from_assign_id($main_plan_ids){
        // [project_id,address_id]がmainのplan_idをキーにして入れ子の配列になったもの  main_idがある案件のカラムは複数存在するのでgroupBy
         return DistributionPlan::select("id as plan_id","main_id","address_id","project_id")->WhereIn("main_id",$main_plan_ids)->get()->groupBy("main_id");
    }


}
