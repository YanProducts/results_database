<?php

// 重複案件のcheckに関する一連の流れ(コントローラーから引き渡し)

namespace App\Actions\ProjectOperator\CheckDispatch;
use App\Actions\ProjectOperator\CheckDispatch\Read as CheckRead;
use App\Actions\ProjectOperator\CheckDispatch\Create as CheckCreate;

class Flow{
    // ファイルが読み込まれてから最初の重複チェック
    public static function check_flow($project_name_and_towns,$place_id){
                // 同じ案件候補が存在する時
        // 同じ案件の可能性があるものを返す(１ヶ月以内は問答無用で「同じ」)
        if(!empty($same_projects_data=CheckRead::check_same_project_data($project_name_and_towns))){
            // 一時保存データ登録
            CheckCreate::store_project_imports($project_name_and_towns,$same_projects_data,$place_id);
        }

        // 同じ案件候補で同じ町目が既に登録されているものを返す
        if(!empty($same_towns_data=CheckRead::check_same_town_data($project_name_and_towns))){
            // 確認テーブルに保存(プロジェクト＆町目で必要な全てのデータを一時挿入)
            CheckCreate::store_plan_imports($project_name_and_towns,$same_towns_data,$place_id);
        }

        return [$same_projects_data,$same_towns_data];
    }
}
