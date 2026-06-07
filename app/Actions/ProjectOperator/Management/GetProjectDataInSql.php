<?php
namespace App\Actions\ProjectOperator\Management;

use App\Models\DistributionPlan;
use App\Models\DistributionRecord;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// 現在sql内部に存在する案件に関するデータの捕捉
class GetProjectDataInSql{

    public static function get_all_data_in_sql(){

        // 現段階での案件名、同案件フラグ、開始日と終了日の捕捉(締切1月以内)
        $data_in_project_table=Project::select("id","project_name","another_project_flag","start_date","end_date",)->where("end_date",">",Carbon::now()->subMonth())->get();

        // 割り当て済みデータの捕捉
        $assined_data=DistributionPlan::select("id","project_id")->get();
        // 配布済みデータの捕捉
        $finished_data=DistributionRecord::select("id","project_id","distribution_count")->get();

        //Projectデータのidに対応する割り当て済みの町目数/配布済みの町目数/配布総定数、配布部数を取得し、Projectデータから直接とった値と連動
        return $data_in_project_table->map(function($each_project_data)use($assined_data,$finished_data){

            return
            [...$each_project_data->toArray(),"town_count"=>$assined_data->where("project_id",$each_project_data["id"])->count(),
            "finished_town_count"=>$finished_data->where("project_id",$each_project_data["id"])->count(),
            "distribution_plan_count"=>0,
            "finished_distribution_count"=>$finished_data->where("project_id",$each_project_data["id"])->pluck("distribution_count")->sum()
            ];
        })->toArray();

    }

}


