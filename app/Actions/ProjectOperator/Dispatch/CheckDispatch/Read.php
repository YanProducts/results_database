<?php

// プロジェクトや町目が既存のものと同じかの確認
namespace App\Actions\ProjectOperator\CheckDispatch;

use App\Exceptions\BusinessException;
use App\Support\CommonModelHelpers\AddressHelpers;
use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use App\Support\CommonModelHelpers\DistributionRecordHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Log;


class Read{
    // 案件が以前と同じものが存在するかの確認
    public static function check_same_project_data($project_name_and_towns){
        // 返却用
        $duplicate_sets=[];

        // プロジェクト名と、そのstart_dateの最も早い日付が前回の30日以内かを調べる
        foreach($project_name_and_towns as $each_project_sets){

            $main_set=$each_project_sets["main"];
            $duplicate_sets=self::add_duplicated_projects_sets($main_set["project_name"],$main_set["date_town_sets"],$duplicate_sets);

            // 該当案件における併配セット
            if(array_key_exists("sub",$each_project_sets)){
                foreach($each_project_sets["sub"] as $each_sub_set){
                   $duplicate_sets=self::add_duplicated_projects_sets($each_sub_set["project_name"],$each_sub_set["date_town_sets"],$duplicate_sets);
               }
            }
        }

        // 同案件可能性があるものを返す
        return $duplicate_sets;
    }

    // 1つ1つ見ていったファイルから、重複しているもののセットを捕捉
    public static function add_duplicated_projects_sets($project_name,$date_town_sets,$duplicate_sets){

            // その案件の最新の同案件フラグナンバーの取得
            $latest_another_project_flag_id=ProjectHelpers::get_latest_project_id_from_name($project_name);

            if($latest_another_project_flag_id!=null && ProjectHelpers::need_user_confirm($latest_another_project_flag_id,$date_town_sets)){
                // 違うプロジェクトの可能性を踏まえて挿入
                // データ挿入は別途、表示用にプロジェクト名のみ入れる
                $duplicate_sets[]=[
                    "nameForUI"=>$project_name,
                    // 既存のidはあれば返り、なければnullが返る
                    "id"=>$latest_another_project_flag_id
                ];
            }

            return $duplicate_sets;
    }

    // 同じ案件内で町名が重なっているデータがあるかの確認
    public static function check_same_town_data($project_name_and_towns){
        $duplicate_sets=[];
        // project_name_and_townsは[テーマ名]=> ["main"=>["project_names"=>"","date_town_sets"=>"","sub"=>["ptojrct_name"と"date_town_sets"がいくつかの配列]]のデータ取得
        foreach($project_name_and_towns as $each_project){
            $main_sets=$each_project["main"]; $sub_sets=$each_project["sub"];
            // メイン案件が重複している場合はリスト追加
            $duplicate_sets=self::add_duplicated_town_sets($main_sets["project_name"],$main_sets["date_town_sets"],$duplicate_sets);
            // サブ案件名が重複している場合はリスト追加
            // 存在確認は前段階で行っている
            foreach($sub_sets as $each_sub){
                $duplicate_sets=self::add_duplicated_town_sets($each_sub["project_name"],$each_sub["date_town_sets"],$duplicate_sets);
            }
         }

        // 同案件可能性の町目が2つあるものを返す
        return $duplicate_sets;

    }

    // 町名の重複を追加(表示のみに使用かつ完全OKか完全アウトのどちらかのため、最低限の情報だけでOK)
    public static function add_duplicated_town_sets($project_name,$date_town_sets,$duplicate_sets){

        // 同じ案件名における最新の案件のid
        $project_id=ProjectHelpers::get_latest_project_id_from_name($project_name);
            // 計画中のものor結果に同案件＆同町目が存在するか
            // 存在確認は前段階で行っている
            foreach($date_town_sets as $date_town_set){
            $address_id=AddressHelpers::get_id_from_city_and_town($date_town_set["city"],$date_town_set["town"]);

            if(DistributionPlanHelpers::data_is_exists($project_id,$address_id) || DistributionRecordHelpers::data_is_exists($project_id,$address_id)){
                $duplicate_sets[]=[
                    // UIの表示用(orojectIdは打ち消し線に使用)
                    // 全部一括でOKかやり直すか
                    "projectId"=>$project_id,
                    "projectName"=>$project_name,
                    "address"=>$date_town_set["city"].$date_town_set["town"],
                ];
             }
            }
        // 同案件可能性の町目が2つあるものを返す
        return $duplicate_sets;
    }


}
