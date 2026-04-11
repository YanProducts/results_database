<?php
// その営業所の指定の期間における案件と町目を変換
// 最終的に、main配列は日付=>メイン案件=>町目、sub配列は日付=>メイン案件⇨サブ案件=>町目に変換
namespace App\Support\BranchManager;

use App\Exceptions\BusinessException;
use App\Support\CommonModelHelpers\AddressHelpers;
use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use App\Utils\DateHelper;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class GetDataInPlaceAndPeriod{

    // 案件の配列を返す
    // 配列は日付=>メイン案件=>[each_sets=>[id(planの),address_name],sub_sets["prject_name"=>,id_sets=>[]]]の形
    public static function get_projects_and_towns_in_the_place_and_period($place_id,$start_offset,$end_offset){
        // 7日後までの、その営業所にあるPlanのcollectionの取得(n+1を避けるため、1回の接続で済ませる)
        $plan_in_the_place_and_period=DistributionPlanHelpers::get_plan_in_the_place_and_period($place_id,DateHelper::get_start_and_end_days("",$start_offset,$end_offset));

        return
        self::get_projects_array($plan_in_the_place_and_period,$start_offset,$end_offset);
    }

    // 配列を作る、配列は日付=>メイン案件=>[each_sets=>[id(planの),address_name],sub_sets["prject_name"=>,id_sets=>[]]]の形
    public static function get_projects_array($plan_in_the_place_and_period,$start_offset,$end_offset){


        //返却用
        $return_sets=[];

        // 取得したコレクションを日付ごとの配列に分ける
        // 〜日後をそれぞれ見ていく
        for($n=$start_offset;$n<=$end_offset-$start_offset;$n++){
            // 日付の取得
            $date=Carbon::now()->addDays($n)->format("Y-m-d");

            // その日付が期間に含まれるものを取得(後に併配案件のところでも使用)
            $plan_sets_in_the_day=$plan_in_the_place_and_period->where("start_date","<=",$date)->where("end_date",">=",$date);


            // 上記のうち、mainがnull(つまりメイン案件))のものを取得し、それをproject_id、つまりメイン案件ごとにまとめる(コレクション->groupByはこれが可能)
            $plan_sets_grouped_by_project_id=$plan_sets_in_the_day->where("main_id",null)->groupBy("project_id");


            // 上記をmain案件⇨["each_sets"=>[id(distribution_plans)⇨,address_name⇨,"sub"=>["project_name"=>"",id_sets=>[]]]の配列に返還

            // メイン案件のeach_setsの中身
            $plan_sets_grouped_by_project_name=$plan_sets_grouped_by_project_id->mapWithKeys(fn($value,$key)=>
                [ProjectHelpers::get_project_name_from_id($key)=>
                    $value->map(fn($inner_value)=>
                        ["id"=>$inner_value["id"],
                          "address_name"=>AddressHelpers::get_city_and_town_from_id($inner_value["address_id"]) ?? throw new BusinessException("データの住所が見つかりません"),
                          "map_number"=>$inner_value["map_number"]
                        ])
                ]
            )->toArray();


            foreach($plan_sets_grouped_by_project_name as $main_project_name=>$each_main_sets){





                if(array_key_exists($main_project_name,$return_sets)){

                }else{
                    $return_sets[$main_project_name]=[
                        "each_sets"=>$each_main_sets,
                        "sub_sets"=>self::get_sub_projects_array($plan_sets_in_the_day->where("main_id","!=",null),$each_main_sets)
                    ];
                }
            }
            }
            return $return_sets;
    }

    // 併配案件の配列を作る
    public static function get_sub_projects_array($plan_sets_in_the_day,$each_main_sets){

        // dd($plan_sets_in_the_day);
        // その日の配布計画(すでにコレクション)から、その日のメイン案件のidの配列のいずれかを親に持つ配列を取得し、案件名ごとにまとめる(つまり配る町目が書かれた併配セット)
        // $sub_plan_sets_grouped_by_id=$plan_sets_in_the_day->whereIn("main_id",array_column($each_main_sets,"id"))->groupBy("project_id");
        $sub_plan_sets_grouped_by_id=$plan_sets_in_the_day->whereIn("main_id",array_column($each_main_sets,"id"))->groupBy("project_id");


        // 上記をプロジェクト名=>each_setsの配列に変換
        $sub_plan_sets=$sub_plan_sets_grouped_by_id->mapWithKeys(
            fn($value,$key)=>[
                    ProjectHelpers::get_project_name_from_id($key)=>array_column($value->toArray(),"main_id"),
             ]
        );

        return $sub_plan_sets->toArray();

    }



}
