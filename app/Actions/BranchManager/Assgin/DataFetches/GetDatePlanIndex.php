<?php

// 日付とプランidを紐づける操作ページで使用するインデックス作成
namespace App\Actions\BranchManager\Assgin\DataFetches;

use Illuminate\Support\Facades\Log;

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
}
