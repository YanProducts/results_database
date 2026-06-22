<?php

// 日付とプランidを紐づける操作ページで使用するインデックス作成
namespace App\Actions\BranchManager\Assgin\DataFetches;

use Illuminate\Support\Facades\Log;


// ここ、rounde_numberを返すようにする！


class GetDatePlanIndex{
    public static function get_date_projects_index($date_sets,$project_and_towns){
        // 返却用
        $return_sets=[];

        // 日付を1つずつみていく
        foreach(array_keys($date_sets) as $date){
            $return_sets[$date]=[];
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
    public static function get_date_projects_index_for_simple($date_sets,$project_and_maps){

        // 返却用
        $return_sets=[];

        // 日付を1つずつみていく
        foreach(array_keys($date_sets) as $date){
            $return_sets[$date]=[];
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
}
