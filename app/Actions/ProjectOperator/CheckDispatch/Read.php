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

        // ①プロジェクト名、②start_dateの最早い日付を取得
        foreach($project_name_and_towns as $project_name=>$date_town_sets){
            if(ProjectHelpers::need_user_confirm($project_name,$date_town_sets)){
                // 違うプロジェクトの可能性を踏まえて挿入
                // データ挿入は別途、表示用にプロジェクト名のみ入れる
                $duplicate_sets[]=[
                    "nameForUI"=>$project_name,
                    // 既存のidはあれば返り、なければnullが返る
                    "id"=>ProjectHelpers::get_latest_project_id_from_name($project_name)
                ];
            }
        }

        // 同案件可能性が2つあるものを返す
        // スコープが別の宣言のため、宣言されていない場合も考慮
        return $duplicate_sets ?? [];
    }

    // 同じ案件内で町名が重なっているデータがあるかの確認
    public static function check_same_town_data($project_name_and_towns){
        // 過去のリストに2つの町目がないか
        // 「ひとまず既存の最新のものと同じ」という前提で取得（違う場合はUIで提出時に操作できる）
        foreach($project_name_and_towns as $project_name=>$each_project_sets){

            // 同じ案件名における最新の案件のid
            $project_id=ProjectHelpers::get_latest_project_id_from_name($project_name);

            // 計画中のものor結果に同案件＆同町目が存在するか
            foreach($each_project_sets as $each_sets){

                $address_id=AddressHelpers::get_id_from_city_and_town($each_sets["city"],$each_sets["town"]);


                // address_idがない時は例外をスローしてエラーページへ(確認の段階だから、まだテーブルはimportも合わせて1つも作られていない)
                // とはいえ、本来はバリデーションの段階で行うべき
                if(empty($address_id)){
                    throw new BusinessException($each_sets["city"].$each_sets["town"]."という町名が見当たりませんでした");
                }

                if(DistributionPlanHelpers::data_is_exists($project_id,$address_id) || DistributionRecordHelpers::data_is_exists($project_id,$address_id)){
                    $duplicate_sets[]=[
                        // UIの表示用
                        // 全部一括でOKかやり直すか
                        "projectName"=>$project_name,
                        "address"=>$each_sets["city"].$each_sets["town"],
                    ];
                }
            }
        }

        // 同案件可能性の町目が2つあるものを返す
        // スコープが別の宣言のため、宣言されていない場合も考慮
        return $duplicate_sets ?? [];

    }
}
