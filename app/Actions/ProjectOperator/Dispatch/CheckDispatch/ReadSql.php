<?php

// プロジェクトや町目が既存のものと同じかの確認
namespace App\Actions\ProjectOperator\Dispatch\CheckDispatch;

use App\Exceptions\BusinessException;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Log;


class ReadSql{
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
    // それを問答無用で同じ案件とみなすか(1ヶ月以内)、他の案件の可能性ありと見なすか
    public static function add_duplicated_projects_sets($project_name,$date_town_sets,$duplicate_sets){

            // その案件の最新の同案件フラグナンバーのprojectのidの取得(今回が初めての場合はnullで返る)
            $latest_another_project_flag_id=ProjectHelpers::get_latest_project_id_from_name($project_name);

            // 上記がnullで返っていない=１度は同じprojectを行ったことがある場合
            // 無条件で同じものと見なすか(前回から1ヶ月以内なら同じ案件とみなす)、importに入れて更新確認するかの確認(前回から1ヶ月以上なら、違う案件の可能性ありとみなす)
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
    // 同じ営業所に限らず重なっていたら同じかは尋ねる
    public static function add_duplicated_town_sets($project_name,$date_town_sets,$duplicate_sets){

        // 同じ案件名における最新の案件のid
        $project_id=ProjectHelpers::get_latest_project_id_from_name($project_name);
        // 上記の案件の市と町のセットの住所id
        $address_ids=AddressHelpers::get_id_lists_from_town_names(collect($date_town_sets)->map(fn($date_town_set)=>$date_town_set["city"].$date_town_set["town"]));

        // 重複の配列(重複している住所IDを取得し、そこから住所の名前の配列を取得。最後にそれをUI用に直す)
        // 予定と結果のどちらかに
        $duplicate_sets=[...$duplicate_sets,...collect(AddressHelpers::get_city_and_town_arrays_key_by_id(collect([...DistributionPlanHelpers::get_address_ids_in_the_projects_in_sql($project_id,$address_ids),...DistributionRecordHelpers::get_address_ids_in_the_projects_in_sql($project_id,$address_ids)])->unique()))->map(fn($each_duplicated_town_name)=>[
                    "projectId"=>$project_id,
                    "projectName"=>$project_name,
                    "address"=>$each_duplicated_town_name,
        ])->values()];

        // 過去のものと同案件可能性の町目があるものを返す
        return $duplicate_sets;
    }


}
