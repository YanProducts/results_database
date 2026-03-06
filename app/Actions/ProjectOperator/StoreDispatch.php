<?php

namespace App\Actions\ProjectOperator;

use App\Models\DistributionPlan;
use App\Models\Project;
use App\Support\Common\PlaceHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use App\Support\ProjectOperator\DispatchCSVProcessor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// 営業所ごとに登録する
class StoreDispatch{



    // 確認が必要な際に、データを仮挿入する




    // projectのsql挿入(日付と名前)
    // むしろProjectテーブルいらないかも！？
    // 今後にProjectの大元を管理することも含めておいておく
    public static function store_projects_data($project_name_and_towns,$place){

        Log::info($project_name_and_towns);

        foreach($project_name_and_towns as $project_name=>$date_town_sets){
            // projectsテーブルに挿入
            // projectsテーブル自体がなくなる可能性はあり
            self::insert_projects_table($$date_town_sets,$project_name,$place);
            // 配布予定テーブルに挿入(プロジェクトIdを取得する必要があるため)
            self::insert_distribution_plans_table($$date_town_sets,$project_name,$place);

        }

    }

    // projectsテーブルに挿入
    // projectsテーブル自体がなくなる可能性はあり
    public static function insert_projects_table($date_town_sets,$project_name,$placeId){
        DB::transaction(function()use($project_name,$date_town_sets,$placeId){

            // １：アップザートで行うこと
            // ２：開始日はより前なら前に。より後ろなら後ろに

            $project=new Project();
            $project->start_date=$date_town_sets["start_date"];
            $project->end_date=$date_town_sets["end_date"];
            $project->projects_name=$project_name;
            $project->placeId=$placeId;
            $project->save();
        });
    }

    // 配布データに町目だけ入れる
    public static function insert_distribution_plans_table($date_town_sets,$project_name,$placeId){
        DB::transaction(function()use($project_name,$date_town_sets,$placeId){
            $distribution_plans=new DistributionPlan();
            //プロジェクトのId
            $distribution_plans->projectId=ProjectHelpers::get_id_from_name($project_name);

            //同じプロジェクト-期限-町目の別プロジェクトの場合(町目を分割した場合など)
            $distribution_plans->same_project_flug="";

            // 営業所Id
            $distribution_plans->placeId=$placeId;
            // 期限
            $distribution_plans->start_date=$date_town_sets["start_date"];
            $distribution_plans->end_date=$date_town_sets["end_date"];
            // 住所
            $distribution_plans->addressId="";
            // 備考(案件担当から)
            $distribution_plans->remark_from_operator="";

            // 同じプロジェクトの町目分割ではないが別の期限のものをどうするか？

        });
    }



}
