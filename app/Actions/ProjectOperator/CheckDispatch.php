<?php

// プロジェクトや町目が既存のものと同じかの確認
namespace App\Actions\ProjectOperator;

use Illuminate\Support\Carbon;
use App\Models\Project;

class CheckDispatch{
    // 案件が以前と同じものかの確認
    public static function check_same_project_data($project_name_and_towns){
        // 別の案件かもよのフラグ
        $another_project_flugs=[];
        // ①プロジェクト名、②start_dateの最早い日付を取得
        foreach($project_name_and_towns as $project_name=>$town_date_sets){
            // 今回投稿のプロジェクトにおいて1番早い日付を取得(単純に文字の順序でそうなる)
            $earliest_start_date=min(array_column($town_date_sets,"start_date"));
            // Datetimeに直す
            $earliest_start_carbon=new Carbon($earliest_start_date);
            // その日付の1ヶ月前の取得
            $one_month_before_start_date=$earliest_start_carbon->subMonth();

            if(Project::where([
                // 同じプロジェクトで1月以上の期間が開いているものがあるかの取得
                ["project_name",$project_name],
                ["end_date","<",$one_month_before_start_date->format("Y-m-d") ]
            ])->exists()){
                // 違うプロジェクトかもよのフラグ
                $another_project_flugs[]=$project_name;
            }
        }
        return $another_project_flugs;
    }

    // 同じ案件内で町名が重なっているとき
    public static function check_same_town_data($project_name_and_towns){
        // 過去のリストに2つの町目がないか
        // 「ひとまず既存の最新のものと同じ」という前提で取得（違う場合はUIで提出時に操作できる）


        // 今回のリストの段階で2つないか

    }

    // 一時保存テーブルに格納
}
