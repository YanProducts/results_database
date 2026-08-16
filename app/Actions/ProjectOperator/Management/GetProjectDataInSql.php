<?php
namespace App\Actions\ProjectOperator\Management;

use App\Models\Address;
use App\Models\DistributionPlan;
use App\Models\DistributionRecord;
use App\Models\Place;
use App\Models\Project;
use App\Models\ProjectPlannedCount;
use App\Support\Common\ModelHelpers\ProjectPlannedCountHelpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// 現在sql内部に存在する案件に関するデータの捕捉
class GetProjectDataInSql{

    // 全データの取得(案件一覧用)
    public static function get_all_data_in_sql(){
        // 現段階での案件名、同案件フラグ、開始日と終了日の捕捉(締切1月以内)
        $data_in_project_table=Project::select("id","project_name","another_project_flag","start_date","end_date",)->where("end_date",">",Carbon::now()->subMonth())->get();

        // 割り当て済みデータの捕捉
        $assined_data=DistributionPlan::select("id","project_id")->get();
        // 配布済みデータの捕捉
        $finished_data=DistributionRecord::select("id","project_id","distribution_count")->get();
        //設定部数リスト
        $total_count_lists=ProjectPlannedCount::select("id","project_id","counts")->get();

        //Projectデータのidに対応する割り当て済みの町目数/配布済みの町目数/配布総定数、配布部数を取得し、Projectデータから直接とった値と連動
        return $data_in_project_table->map(function($each_project_data)use($assined_data,$finished_data,$total_count_lists){

            return
            [...$each_project_data->toArray(),"town_count"=>$assined_data->where("project_id",$each_project_data["id"])->count(),
            "finished_town_count"=>$finished_data->where("project_id",$each_project_data["id"])->count(),
            // round_number別に取得ではなく合計取得(日毎取得は別途取得)
            "distribution_plan_count"=>$total_count_lists->where("project_id",$each_project_data["id"])->pluck("counts")->sum(),
            "finished_distribution_count"=>$finished_data->where("project_id",$each_project_data["id"])->pluck("distribution_count")->sum()
            ];
        })->toArray();

    }

    // 日毎のデータの取得
    // 開始日付=>[営業所=>[案件名(projectのidで分けている(同じ案件名でも違う案件ならidが違う)。同じ日で同じ営業所が2つ以上あればround_number)=>[終了日&併配リスト&市のリスト&合計]]]という形式にする
    public static function get_data_by_day(){

        // 締切が1月前以内の案件を配布予定リストから取得(設定合計なども書かれたplancountsのテーブル)。最終的には型式を直す
        $plans_and_counts=ProjectPlannedCountHelpers::get_plans_by_start_day();

        // 2度以上使うもの
        $project_ids=$plans_and_counts->pluck("project_id");
        $place_ids=$plans_and_counts->pluck("place_id");

        // 開始日付=>[営業所=>[案件名=>[終了日&併配リスト&市のリスト]]]

        // 検索用に案件名の取得(idがplanの締切1月以内に相当)
        $project_name_corresponds_id=Project::select("id","project_name")->whereIn("id",$project_ids)->pluck("project_name","id");

        // 営業所名の取得(idがplanの締切1月以内に相当)// id対応のためvaluesつけない！
        $place_name_corresponds_id=Place::select("id","place_name")->whereIn("id",$place_ids)->pluck("place_name","id")->unique();


        // countsのテーブルと照合して市名の列挙に取得( same_project_flagとround_numberのところもう少し!!!)
        // 列挙のみなので、main_idがnullのみ取得
        $distribution_plans=DistributionPlan::select("start_date","end_date","address_id","project_id","same_project_flag","place_id")->whereIn("project_id",$project_ids)->whereIn("place_id",$place_ids)->whereIn("start_date",$plans_and_counts->pluck("start_date"))->where("main_id",null)->get();

        // 列挙用の市名の取得(idがplanの締切1月以内に相当)
        $city_name_corresponds_id=Address::select("id","city")->whereIn("id",$distribution_plans->pluck("address_id"))->pluck("city","id");

        //   開始日付=>[営業所=>[案件名(projectのidで分けている(同じ案件名でも違う案件ならidが違う)。同じ日で同じ営業所が2つ以上あればround_number)=>[終了日&併配リスト&市のリスト&合計]]]
        return OverviewByDayFormatter::change_data_for_overview_by_day($plans_and_counts,$project_name_corresponds_id,$place_name_corresponds_id,$distribution_plans,$city_name_corresponds_id);

    }


}


