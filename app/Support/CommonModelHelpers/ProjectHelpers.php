<?php
// ProjectModelに関するサポート関数

namespace App\Support\CommonModelHelpers;

use App\Exceptions\BusinessException;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProjectHelpers{
    // idから案件名を取得(見つからなかったらNullが返る)
    public static function get_project_name_from_id($project_id){
        return Project::find($project_id)?->project_name;
    }
    // 案件名から、その案件名に該当する最新のidを返還
    public static function get_latest_project_id_from_name($project_name){

        // ない場合は空のコレクションからidも存在せずnullが返却
       return
        Project::where("project_name",$project_name)->orderBy("another_project_flag","desc")->value("id");
    }
    // 最新の同案件No.を返す
    public static function get_latest_another_project_flag($project_name){

        // ない場合は空のコレクションからidも存在せずnull(??で0を返却)
       return
        Project::where("project_name",$project_name)->orderBy("another_project_flag","desc")->value("another_project_flag") ?? 0;
    }

    // 更新するか作成するかの選択(そのプロジェクトのidと日付が引数)
    public static function need_user_confirm($latest_another_project_flag_id,$date_town_sets){
            // 同じプロジェクトで1月以上の期間が開いているものがあるかの取得
            // 前回のプロジェクト終了から、今回のプロジェクト開始が、１ヶ月より長いものがあれば、まずは確認後、処理を決定
            if(Project::where([
                ["id",$latest_another_project_flag_id],
                ["end_date","<",Carbon::parse(min(array_column($date_town_sets,"start_date")))->subMonth() ]
            ])->exists()){
                // １ヶ月以上間あり。まずは確認
                return true;
            }
            //
            // １ヶ月以内。問答無用で更新
            return false;
    }

    //該当プロジェクトの最新の同案件ナンバーのクエリビルダを返す
    public static function max_flag_query_in_the_projects($project_name){
        $flag = self::get_latest_another_project_flag($project_name);

        return Project::where([
            ["project_name","=",$project_name],["another_project_flag","=",$flag]
        ]);
    }
    // 該当営業所の該当機関に来ている案件を返す
    public static function get_projects_in_the_place_and_period($place_id,$date_sets){

        // dateが存在しないとき (issetは複数キーの同時チェック可能)
        if (!isset($date_sets['start'], $date_sets['end'])) {
            throw new BusinessException("日付取得時のエラーです");
        }


        return Project::where("place_id",$place_id)->where("start_date","<=",$date_sets["start"])
        ->where("end_date",">=",$date_sets["end"])
        ->get();
    }

}
