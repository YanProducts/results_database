<?php

namespace App\Actions\ProjectOperator;

use App\Models\DistributionPlan;
use App\Models\Project;
use App\Support\ProjectOperator\DispatchCSVProcessor;

// 営業所ごとに登録する
class StoreDispatch{

    // 案件データを営業所に登録
    public static function send_projects_to_branch($request){
        //営業所の取得
        $place=$request->place;

        // CSVから案件名=>[town,start,end]の入れ子配列の取得
         $project_name_and_towns=DispatchCSVProcessor::get_data_in_files($request->fileSets);
 
         // projectsに案件データを入れる（併配含む）
        //  projectsは現在は必要ないが、外部キー等の関係上、入力しておき、確認などに使用する
        self::store_projects_data($project_name_and_towns);

        // distribution_plansに案件Idと町目を入れる
        self::store_distribution_plan($place,$project_name_and_towns);

    }

    // projectのsql挿入(日付と名前)
    // むしろProjectテーブルいらないかも！？
    // 今後にProjectの大元を管理することも含めておいておく
    public static function store_projects_data($project_name_and_towns){

        $project=new Project();

    }

    // 配布データに町目だけ入れる
    public static function store_distribution_plan($project_name_and_towns){
        $disribution_plan=new DistributionPlan();

    }



}
