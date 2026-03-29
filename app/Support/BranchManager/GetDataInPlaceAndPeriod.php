<?php
// その営業所の指定の期間における案件と町目を変換
// 最終的に、main配列は日付=>メイン案件=>町目、sub配列は日付=>メイン案件⇨サブ案件=>町目に変換
namespace App\Support\BranchManager;

use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use App\Utils\DateHelper;
use Carbon\Carbon;

class GetDataInPlaceAndPeriod{
    // メイン案件の配列を作る、配列は日付=>メイン案件=>[id(投稿用)
    public static function create_main_projects_array($place_id,$start_offset,$end_offset){
        // 5日後までの、その営業所にあるPlanのcollectionの取得(n+1を避けるため、1回の接続で済ませる)
        $plan_in_the_place_and_period=DistributionPlanHelpers::get_plan_in_the_place_and_period($place_id,DateHelper::get_start_and_end_days("",$start_offset,$end_offset));

        // 取得したコレクションを日付ごとの配列に分ける
        // 〜日後をそれぞれ見ていく
        for($n=$start_offset;$n<=$end_offset-$start_offset;$n++){
            // 日付の取得
            $date=Carbon::now()->addDays($n)->format("Y-m-d");
            // その日付が期間に含まれるplanをコレクションで取得し、project_idでまとめる(コレクション->groupByはこれが可能)
            $plan_in_the_place_and_period->where("start_date",">=",$date)->where("end_date","<=",$date)->groupBy("project_id");

            // 配列をプロジェクト名から返す
            $plan_in_the_place_and_period->mapWithKey(fn($value,$key)=>
                [ProjectHelpers::get_project_name_from_id($key)=>$value]
            );


            // キーをプロジェクトのidからプロジェクト名に変更
            $return_sets=[
                $date=>$plan_in_the_place_and_period
            ];
        }


    }
    // 併配案件の配列を作る
    public static function create_sub_projects_array($place,$start,$end){

    }
    public static function get_projects_and_towns_in_the_place_and_period($place_id,$start,$end){
        return[
            "main"=>self::create_main_projects_array($place_id,$start,$end),
            "sub"=>self::create_sub_projects_array($place_id,$start,$end)
        ];
    }
}
