<?php

namespace App\Actions\Shared\StoreReports;

// 案件のみから提出された版の投稿→保存に向けたデータ取得の流れ(配列で返す)
class PrepareForFromProjectOnlyStoreDistributionRecord{

    // 保存に必要なデータ(配布済に変更するassignidと保存用配列)を取得
    public static function prepare_for_store($date,$report_data,$staff_id,$from_edit=false){

        // report_dataはplan_idが入っている(パラメータはidentifier、メイン案件のみ)

        // 上記のメイン案件を持つ(identifierはメイン案件のみのplan_id)サブ案件のproject_idとplan_idの取得

        // 最終的に"distribution_date","distribution_count","address_id","project_id","staff_id","remark","plan_id"を入れ子の返す
        // これはメインのみ
        $main_data_to_store_array=[];
        foreach($report_data as $each_data){
            $main_data_to_store_array[]=[
                "distribution_date"=>$date,
                "distribution_counts"=>"",
                "address_id"=>"",
                "project_id"=>"",
                "staff_id"=>$staff_id,
                "remark"=>"",
                "plan_id"=>$each_data["identifier"]
            ];
        }

        // サブ案件


    }

}
