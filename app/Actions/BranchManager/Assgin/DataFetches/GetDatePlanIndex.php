<?php

// 日付とプランidを紐づける操作ページで使用するインデックス作成
namespace App\Actions\BranchManager\Assgin\DataFetches;

use Illuminate\Support\Facades\Log;


// ここ、rounde_numberを返すようにする！


class GetDatePlanIndex{
    public static function get_date_projects_index($date_sets,$project_and_towns,$assigned_days){
        // 返却用
        $return_sets=[];

        // 日付を1つずつみていく
        foreach(array_keys($date_sets) as $date){
            $return_sets[$date]=[];

            // 割当済なら次の日へ
            if($assigned_days->contains($date)){
                continue;
            }

            // 案件セットを1つずつみていく
            foreach($project_and_towns as $project_name=>$each_project_sets){
                if(collect($each_project_sets["each_sets"])->contains(fn($each_set)=>$each_set["start_date"]<=$date && $each_set["end_date"]>=$date)){
                    array_push($return_sets[$date],$project_name);
                }
            }
        }

    // date=>その日に少しでも行くことのできるprojectsのセットの形で返却
    return $return_sets;

    }


    // 本来は一緒にするが、バグ防止のため。ひとまず新たに作成
    public static function get_date_projects_index_for_simple($date_sets,$project_and_maps,$assigned_days){

        // 返却用
        $return_sets=[];

        // 日付を1つずつみていく
        foreach(array_keys($date_sets) as $date){
            $return_sets[$date]=[];

            // 割当済なら次の日へ
            if($assigned_days->contains($date)){
                continue;
            }

            // 案件セットを1つずつみていく
            foreach($project_and_maps as $project_name=>$each_project_sets_with_round_number){
                // round_numberでの小分けを1つずつ見ていく
                foreach($each_project_sets_with_round_number as $round_number=>$each_project_sets){
                    if(collect($each_project_sets["each_sets"])->contains(fn($each_set)=>$each_set["start_date"]<=$date && $each_set["end_date"]>=$date)){
                        array_push($return_sets[$date], ["project_name"=>$project_name,"round_number"=>$round_number]);
                    };
                }
            }
        }

    // date=>その日に少しでも行くことのできるprojectsのセットの形で返却
    return $return_sets;

    }


        // 案件のみ版の配列作成(日付=>projectPlannedのid,表示用の案件名(roundNumber考慮),説明用セット(併配セット、市の名前)])
    public static function get_project_data_for_project_only_pattern($date_sets,$projects_sets,$project_and_towns,$assigned_days){
        $return_sets=[];
        foreach(array_keys($date_sets) as $date){

        }


        // 日付=>[]
        return [];
    }




}
