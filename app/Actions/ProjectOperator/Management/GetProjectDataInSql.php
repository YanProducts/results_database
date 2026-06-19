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
    // 開始日付=>[営業所=>[案件名=>[終了日&併配リスト&市のリスト]]]という形式にする
    // same_project_flagは「過去のものと同じか」//round_numberは同じ投稿で同じメイン案件が別々に投稿されたとき
    public static function get_data_by_day(){

        // 締切が1月前以内の案件を配布予定リストから取得
        $distribution_plans=DistributionPlan::select("id","project_id","round_number","place_id","start_date","end_date","address_id","main_id")->where("end_date",">",Carbon::now()->subMonth())->get();

        // 上記のうち、メイン案件のみのデータ全てを抽出
        $main_distribution_plans=$distribution_plans->where("main_id",null);

        // 上記のうち、メイン案件のみの配列を重なりなしで取得(project_idとround_numberをセットで取得しpluck、uniueを使う)

        // 上記のうち、サブ案件のみを抽出し、メイン案件=>という形式にして、その内部でuniqueする
        $sub_distribution_plans=$distribution_plans->where("main_id","<>","null")->groupBy("main_id");

        // 案件名の取得(idがplanの締切1月以内に相当)// id対応のためvaluesつけない！//uniqueはつけない(単純な併配リストの時にround_numberが違う場合を考慮)
        $project_name_corresponds_id=Project::select("id","project_name")->whereIn("id",$distribution_plans->pluck("project_id"))->pluck("project_name","id");

        // 市名の取得(idがplanの締切1月以内に相当)// id対応のためvaluesつけない！
        // uniqueをつけたら対応する市が取得できなくなる可能性を考慮(町が違えば当然変わってくる)
        $city_name_corresponds_id=Address::select("id","city")->whereIn("id",$distribution_plans->pluck("address_id"))->pluck("city","id");

        // 営業所名の取得(idがplanの締切1月以内に相当)// id対応のためvaluesつけない！
        $place_name_corresponds_id=Place::select("id","place_name")->whereIn("id",$distribution_plans->pluck("place_id"))->pluck("place_name","id")->unique();

        // planを(collectionにした後で)開始日ごとにまとめる
        $plan_group_by_start_date=$main_distribution_plans->groupBy("start_date");


        // さらに内部を営業所名で分割
        $grouped_data=$plan_group_by_start_date->mapWithKeys(
            fn($each_plan_by_start_date,$key1)=>[$key1=>$each_plan_by_start_date->groupBy("place_id")
            ->mapWithKeys(fn($each_plan_by_place,$key_by_place)=>[$place_name_corresponds_id[$key_by_place]=>

          // planをmain案件+round_numberが同じものでまとめ１：併配案件リスト、２：市名リスト、３：最も遅い終了日でまとえる

            $each_plan_by_place->groupBy(fn($row)=>$row["project_id"]."_".$row["round_number"])->mapWithKeys(function($each_plan_by_project,$key_by_project)use($project_name_corresponds_id,$sub_distribution_plans,$city_name_corresponds_id){

                // メイン案件名(キー)の操作
                $under_ber_point=mb_strpos($key_by_project,"_");
                $main_project_id=mb_substr($key_by_project,0,$under_ber_point);
                $project_name=$project_name_corresponds_id[$main_project_id];
                $key_name=mb_substr($key_by_project,$under_ber_point+1)==0 ? $project_name : $project_name."（".mb_substr($key_by_project,$under_ber_point+1)."回目）";


                // サブ案件名を一挙取得
                $sub_lists=implode(",",(($sub_distribution_plans[$main_project_id] ?? collect())->pluck("project_id")->map(fn($sub_project_id)=>$project_name_corresponds_id[$sub_project_id]))->toArray());

                // 市の名前を一挙取得
                $city_lists=implode(",",$each_plan_by_project->map(fn($each_plan)=>$city_name_corresponds_id[$each_plan["address_id"]])->unique()->toArray());

                // 最も遅い終了日の取得
                $lastest_end_date=$each_plan_by_project->max("end_date");

                return
                [
                $key_name=>[
                    "sub_lists"=>$sub_lists,
                    "city_lists"=>$city_lists,
                    "end_date"=>$lastest_end_date
                ]];//メイン案件でのgroupBy
            })
            ])//営業所でのgroupby
         ]);//日付でのgroupby


        return $grouped_data;
    }


}


