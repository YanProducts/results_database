<?php
namespace App\Actions\Clerical;

use App\Models\Project;
use App\Models\DistributionPlan;
use App\Models\DistributionRecord;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Constants\Date;

// 現在SQLに入っているデータを確認する
class GetDataInSql{

    // sqlから取得
    public static function get_data_in_sql(){
        // 案件リスト(締切後３ヶ月以内、締切日ごとに並べる)
        $project_sets=Project::select("id","project_name","end_date")->where("end_date",">", CarbonImmutable::now()->addDays(Date::EndOffsetForClericalExport))->get()->keyBy("id");

        //プロジェクトごとの営業所に振られた町目数
        $town_counts=DistributionPlan::select("project_id", DB::raw("count(*) as planned_town_counts"))->groupBy("project_id")->get();

        // 記入された町目数
        $reported_counts=DistributionRecord::select("project_id", DB::raw("count(*) as recorded_town_counts, sum(distribution_count) as sum_distribution_counts"))->groupBy("project_id")->get();

        return [$project_sets,$town_counts,$reported_counts];
    }

    public static function data_change($project_sets,$town_counts,$reported_counts){
        $projects_in_sql=[];
        foreach($project_sets as $project_id=>$project_data){
            $reported_data=$reported_counts[$project_id];
            $projects_in_sql[$project_id]=[
                "project_name"=>$project_data["project_name"],
                "end_date"=>$project_data["end_date"],
                "planned_town_counts"=>$town_counts[$project_id]["planned_town_counts"],
                "reported_town_counts"=>$reported_data["recorded_town_counts"],
                "reported_distribution_counts"=>$reported_data["sum_distribution_counts"]
            ];
        }
        return $projects_in_sql;
    }
}
