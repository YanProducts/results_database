<?php

// 重複案件のcheckに関する一連の流れ(コントローラーから引き渡し)

namespace App\Actions\ProjectOperator\CheckDispatch;
use App\Actions\ProjectOperator\CheckDispatch\Read as CheckRead;
use App\Actions\ProjectOperator\CheckDispatch\Create as CheckCreate;
use App\Actions\ProjectOperator\CheckDispatch\Delete as CheckDelete;
use App\Actions\ProjectOperator\StoreDispatch;
use Illuminate\Support\Facades\Auth;

class Flow{
    // ファイルが読み込まれてから最初の重複チェック
    public static function check_flow($project_name_and_towns,$place_id){
        // 同じ案件の可能性があるものを返す(１ヶ月以内は問答無用で「同じ」)
        $same_projects_data=CheckRead::check_same_project_data($project_name_and_towns);
        // 同じ案件候補で同じ町目が既に登録されているものを返す
        $same_towns_data=CheckRead::check_same_town_data($project_name_and_towns);

        // どちらかが引っ掛かれば確認ページに行くので、両方のデータを一時保存に挿入
        if(!empty($same_projects_data) || !empty($same_towns_data)){
            // 一時保存データ登録
            CheckCreate::store_project_imports($project_name_and_towns,$same_projects_data,$place_id);
            // 確認テーブルに保存(プロジェクト＆町目で必要な全てのデータを一時挿入)
            CheckCreate::store_plan_imports($project_name_and_towns,$same_towns_data,$place_id);
        }

        return [$same_projects_data,$same_towns_data];
    }

    // 確認後の流れ
    public static function after_confirm_flow($new_projects){
        // 新案件かどうかを考慮しての案件の更新
        StoreDispatch::upsert_after_confirmation_to_projects($new_projects);

        // 町目の更新(そのまま更新)
        StoreDispatch::upsert_after_confirmation_to_plans($new_projects);

        // 案件の消去
        CheckDelete::automatic_delete_from_same_user();

    }
}
