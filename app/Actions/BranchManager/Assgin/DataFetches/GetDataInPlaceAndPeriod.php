<?php
// その営業所の指定の期間における案件と町目を変換
// 最終的に、main配列は日付=>メイン案件=>町目、sub配列は日付=>メイン案件⇨サブ案件=>町目に変換
namespace App\Actions\BranchManager\Assgin\DataFetches;

use App\Exceptions\BusinessException;
use App\Support\CommonModelHelpers\AddressHelpers;
use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use App\Utils\DateHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GetDataInPlaceAndPeriod{

    // 案件の配列を返す
    // 配列はメイン案件=>[each_sets=>[id(planの),address_name],sub_sets["prject_name"=>,id_sets=>[]]]の形
    public static function get_projects_and_towns_in_the_place_and_period($place_id,$start_offset,$end_offset){

        // 設定日後までの、その営業所にあるPlanのcollectionの取得(n+1を避けるため、1回の接続で済ませる)
        $plan_in_the_place_and_period=DistributionPlanHelpers::get_plan_in_the_place_and_period($place_id,DateHelper::get_start_and_end_days("",$start_offset,$end_offset));

        return
        self::get_projects_array($plan_in_the_place_and_period);
    }

    // 配列を作る、配列は日付=>メイン案件=>[each_sets=>[id(planの),address_name],sub_sets["prject_name"=>,id_sets=>[]]]の形
    public static function get_projects_array($plan_in_the_place_and_period){

        //planリストのプロジェクトidに対応するプロジェクト名を一括取得(n+1を防止するため)
        $project_names_key_by_project_id=ProjectHelpers::get_project_names_array_key_by_id($plan_in_the_place_and_period->select("project_id")->toArray());

        // planリストの住所idに対応する住所を一括で取得(n+1防止)
        $address_names_key_by_id=AddressHelpers::get_city_and_town_arrays_key_by_id($plan_in_the_place_and_period->select("address_id")->toArray());

        //返却用
        $return_sets=[];

            // メイン案件リスト
            $main_plan_sets_grouped_by_project_id=$plan_in_the_place_and_period->where("main_id",null)->groupBy("project_id");

            // 上記をmain案件⇨["each_sets"=>[id(distribution_plans)⇨,address_name⇨,"sub"=>["project_name"=>"",id_sets=>[]]]の配列に返還

            // メイン案件のeach_setsの中身
            $main_plan_sets_grouped_by_project_name=$main_plan_sets_grouped_by_project_id->mapWithKeys(fn($value,$key)=>
                [$project_names_key_by_project_id[$key]=>
                    $value->map(fn($inner_value)=>
                        ["id"=>$inner_value["id"],
                          "address_name"=>$address_names_key_by_id[$inner_value["address_id"]] ?? throw new BusinessException("データの住所が見つかりません"),
                          "map_number"=>$inner_value["map_number"],
                          "start_date"=>$inner_value["start_date"],
                          "end_date"=>$inner_value["end_date"]
                        ])
                ]
            )->toArray();

            foreach($main_plan_sets_grouped_by_project_name as $main_project_name=>$each_main_sets){

                if(array_key_exists($main_project_name,$return_sets)){
                // 同名のプロジェクトを複数投稿する時



                }else{
                    $return_sets[$main_project_name]=[
                        "each_sets"=>$each_main_sets,
                        "sub_sets"=>self::get_sub_projects_array($project_names_key_by_project_id,$plan_in_the_place_and_period->where("main_id","!=",null),$each_main_sets)
                    ];
                }
            }
            return $return_sets;
    }

    // 併配案件の配列を作る
    public static function get_sub_projects_array($project_names_key_by_project_id,$plan_sets_in_the_day,$each_main_sets){

        // その日の配布計画(すでにコレクション)から、その日のメイン案件のidの配列のいずれかを親に持つ配列を取得し、案件名ごとにまとめる(つまり配る町目が書かれた併配セット)
         $sub_plan_sets_grouped_by_id=$plan_sets_in_the_day->whereIn("main_id",array_column($each_main_sets,"id"))->groupBy("project_id");

        // 上記をプロジェクト名=>each_setsの配列に変換
        $sub_plan_sets=$sub_plan_sets_grouped_by_id->mapWithKeys(
            fn($value,$key)=>[
                $project_names_key_by_project_id[$key]=>array_column($value->toArray(),"main_id"),
             ]
        );
        return $sub_plan_sets->toArray();

    }



}
