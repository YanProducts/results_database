<?php
// ProjectModelに関するサポート関数

namespace App\Support\CommonModelHelpers;

use App\Models\Project;
use Carbon\Carbon;

class ProjectHelpers{
    // 案件名から、その案件名に該当する最新のプロジェクト名を返還
    public static function get_latest_project_id_from_name($project_name){
        // ない場合は空のコレクションからidも存在せずnullが返却
       return
        Project::where("project_name",$project_name)->orderBy("another_project_flag","desc")->value("id");
    }
    // 最新の同案件No.を返す
    public static function get_latest_another_project_flag($project_name){
        // ない場合は空のコレクションからidも存在せずnullが返却
       return
        Project::where("project_name",$project_name)->orderBy("another_project_flag","desc")->value("another_project_flag");
    }

    // 更新するか作成するかの選択
    public static function need_user_confirm($project_name,$date_town_sets){
            // 同じプロジェクトで1月以上の期間が開いているものがあるかの取得
            // 前回のプロジェクト終了から、今回のプロジェクト開始が、１ヶ月より長いものがあれば、まずは確認後、処理を決定
            if(Project::where([
                ["project_name",$project_name],
                ["end_date","<",Carbon::parse(min(array_column($date_town_sets,"start_date")))->subMonth() ]
            ])->exists()){
                // １ヶ月以上間あり。まずは確認
                return true;
            }
            //
            // １ヶ月以内。問答無用で更新
            return false;
    }
}
