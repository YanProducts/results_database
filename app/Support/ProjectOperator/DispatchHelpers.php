<?php

// 案件割り振りの際のヘルパー関数
namespace App\Support\ProjectOperator;

use App\Models\Project;
use App\Support\CommonModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Log;

class DispatchHelpers{
    // 確認後、新案件ではないデータを、upsert用に配列を変換
    public static function change_after_confirm_post_data_for_upsert($project_imports,$new_projects){

     return $project_imports
        ->filter(fn($import)=>!in_array($import->project_id,$new_projects))
        ->mapWithKeys(fn($import)=>
                [$import->project_name=>[
                    ["start_date"=>$import->start_date,
                    "end_date"=>$import->end_date]
                ]]
        )->toArray();
    }

    // 新たに投稿されたファイルと、既存のSQLデータを比較して日付が最も早い日にち返す
    public static function get_earliest_start_date($project_name,$date_town_sets){

        // 渡されたデータで最も早い開始日の取得
        $min_data_in_sets=min(array_column($date_town_sets,"start_date"));

        // 現在のデータの取得
        $now_data_in_sql=ProjectHelpers::max_flag_query_in_the_projects($project_name)->value("start_date");

        return $now_data_in_sql==null ? $min_data_in_sets :  min($now_data_in_sql,$min_data_in_sets);

    }

    // 新たに投稿されたファイルと、既存のSQLデータを比較して日付が最も早い日にち返す
    public static function get_lateest_end_date($project_name,$date_town_sets){

        // 渡されたデータで最も遅い開始日の取得
        $max_data_in_sets=max(array_column($date_town_sets,"end_date"));

        // 現在の最も遅い開始日
        $now_data_in_sql=ProjectHelpers::max_flag_query_in_the_projects($project_name)->value("end_date");

        // 最大値は元のデータが存在しない場合はnullと渡されたデータの比較で渡されたデータになる
        return $now_data_in_sql ==null ? $max_data_in_sets :max($now_data_in_sql,$max_data_in_sets);

    }
}
