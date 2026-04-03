<?php
// その営業所の指定の期間における案件と町目を変換
// 最終的に、main配列は日付=>メイン案件=>町目、sub配列は日付=>メイン案件⇨サブ案件=>町目に変換
namespace App\Support\BranchManager;

use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use App\Utils\DateHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GetDataInPlaceAndPeriod{

    // 案件の配列を返す(メイン案件、サブ案件)
    public static function get_projects_and_towns_in_the_place_and_period($place_id,$start_offset,$end_offset){
        // 7日後までの、その営業所にあるPlanのcollectionの取得(n+1を避けるため、1回の接続で済ませる)
        $plan_in_the_place_and_period=DistributionPlanHelpers::get_plan_in_the_place_and_period($place_id,DateHelper::get_start_and_end_days("",$start_offset,$end_offset));

        [self::get_main_projects_array($plan_in_the_place_and_period->where("main_id",null),$start_offset,$end_offset),self::get_sub_projects_array($plan_in_the_place_and_period->notWhere("main_id","!=",null),$start_offset,$end_offset)];
    }

    // メイン案件の配列を作る、配列は日付=>メイン案件=>[id(投稿用),town_sets]
    public static function get_main_projects_array( $plan_in_the_place_and_period,$start_offset,$end_offset){

        //返却用
        $return_sets=[];

        // 取得したコレクションを日付ごとの配列に分ける
        // 〜日後をそれぞれ見ていく
        for($n=$start_offset;$n<=$end_offset-$start_offset;$n++){
            // 日付の取得
            $date=Carbon::now()->addDays($n)->format("Y-m-d");


// Log::info($date);
//             Log::info($plan_in_the_place_and_period->toArray());


            // その日付が期間に含まれるものを、project_idでまとめる(コレクション->groupByはこれが可能)
            $plan_sets_grouped_by_projects=$plan_in_the_place_and_period->where("start_date","<=",$date)->where("end_date",">=",$date)->groupBy("project_id");




            // プロジェクト名を返す
            // 配列じゃんw
            $project_name=$plan_sets_grouped_by_projects->mapWithKeys(fn($value,$key)=>
                [ProjectHelpers::get_project_name_from_id($key)=>$value]
            );


            Log::info($project_name);
            Log::info($plan_sets_grouped_by_projects);dd(1);


            // キーをプロジェクトのidからプロジェクト名に変更
            $return_sets[$date][$project_name]=[
                "id"=>$plan_sets_grouped_by_projects["id"],
                // ここが難しい
                "plan_sets"=>$plan_sets_grouped_by_projects
             ];
             Log::info($return_sets);
             dd("a");
        }


    }
    // 併配案件の配列を作る
    public static function get_sub_projects_array($place,$start,$end){

    }


}
