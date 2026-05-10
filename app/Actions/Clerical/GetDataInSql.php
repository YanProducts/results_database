<?php
namespace App\Actions\Clerical;

use App\Models\Project;
use App\Models\DistributionPlan;
use App\Models\DistributionRecord;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Constants\Date;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Log;

// 現在SQLに入っているデータを確認する
class GetDataInSql{

    // 現在SQLにある案件-配布の集約的データsqlから取得
    public static function get_aggregated_data_in_sql(){
        // 案件リスト(締切後３ヶ月以内、締切日ごとに並べる)
        $project_sets=Project::select("id","project_name","end_date","is_complete")->where("end_date",">", CarbonImmutable::now()->addDays(Date::EndOffsetForClericalExport))->get()->keyBy("id");

        //プロジェクトごとの営業所に振られた町目数
        $town_counts=DistributionPlan::select("project_id", DB::raw("count(*) as planned_town_counts"))->groupBy("project_id")->get()->keyBy("project_id");

        // 記入された町目数
        $recorded_counts=DistributionRecord::select(DB::raw("project_id, count(*) as recorded_town_counts, sum(distribution_count) as sum_distribution_counts"))->groupBy("project_id")->get()->keyBy("project_id");

        return [$project_sets,$town_counts,$recorded_counts];
    }

    // 現在、プランされている案件のうち、id(projectsテーブルの)にある案件の町目id,部数,スタッフ(複数人の場合あり)を返す
    public static function get_detailed_planned_data_by_project_ids($project_ids){

        // DistrbutionPlanから、そのプロジェクトに対応するplanのidを取得し、そのidと町目idを取得(未配布や0枚の可能性もあるので、Recordのみからは取得しない)
        $plans_in_project_ids=DistributionPlan::select("id","project_id","address_id")->whereIn("project_id",$project_ids)->get();

        // プロジェクトIdに対応するプロジェクト名とanother_project_flagの取得
        $project_sets=ProjectHelpers::get_project_names_with_another_project_flag_array_key_by_id($project_ids);

        // n+1対策のため、上記のaddress_idに相当するaddress_nameを一括取得
        $address_sets=AddressHelpers::get_city_and_town_arrays_key_by_id($plans_in_project_ids->pluck("address_id"));

        // n+1対策のため、上記のplan_idに相当する配布結果セットを一括取得し、plan_idごとにまとめる
        $distribution_record_sets=DistributionRecordHelpers::get_record_sets_in_the_plan_ids_group_by_plan_ids($plans_in_project_ids->pluck("id"));

        return [$plans_in_project_ids,$project_sets,$address_sets,$distribution_record_sets];


    }


}
