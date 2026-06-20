<?php
namespace App\Actions\ProjectOperator\Management;

use App\Models\Address;
use App\Models\DistributionPlan;
use App\Models\DistributionRecord;
use App\Models\Place;
use App\Models\Project;
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

    // 日毎のデータの取得
    // 以下だと「同案件で何回目か」は取得できても、「同じコードの違う案件」で何回目かは取得できない！！！
    // 開始日付=>[営業所=>[案件名=>[round_number(何回目か))=>[終了日&併配リスト&市のリスト]]]という形式にする
    // same_project_flagは「過去のものと同じか」//round_numberは同じ投稿で同じメイン案件が別々に投稿されたとき
    public static function get_data_by_day(){

        // 締切が1月前以内の案件を配布予定リストから取得
        $distribution_plans=DistributionPlan::select("id","project_id","round_number","place_id","start_date","end_date","address_id","main_id")->where("end_date",">",Carbon::now()->subMonth())->get();

        // 案件名の取得(idがplanの締切1月以内に相当)// id対応のためvaluesつけない！//uniqueはつけない(単純な併配リストの時にround_numberが違う場合を考慮)

        // 現状、same_project_flagには触れられていない！！！！！

        $project_name_corresponds_id=Project::select("id","project_name")->whereIn("id",$distribution_plans->pluck("project_id"))->pluck("project_name","id");

        // 市名の取得(idがplanの締切1月以内に相当)// id対応のためvaluesつけない！
        // uniqueをつけたら対応する市が取得できなくなる可能性を考慮(町が違えば当然変わってくる)
        $city_name_corresponds_id=Address::select("id","city")->whereIn("id",$distribution_plans->pluck("address_id"))->pluck("city","id");

        // 営業所名の取得(idがplanの締切1月以内に相当)// id対応のためvaluesつけない！
        $place_name_corresponds_id=Place::select("id","place_name")->whereIn("id",$distribution_plans->pluck("place_id"))->pluck("place_name","id")->unique();

        // フォーマット形式に合わせる
        $grouped_data=OverviewByDayFormatter::change_data_for_overview_by_day($distribution_plans,$project_name_corresponds_id,$city_name_corresponds_id,$place_name_corresponds_id);

        return $grouped_data;
    }


}


