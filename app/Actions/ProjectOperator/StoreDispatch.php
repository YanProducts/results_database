<?php

namespace App\Actions\ProjectOperator;

use App\Models\DistributionPlan;
use App\Models\Project;
use App\Support\CommonModelHelpers\AddressHelpers;
use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// 営業所ごとに登録する
class StoreDispatch{


    // projectのsql挿入(日付と名前)
    // むしろProjectテーブルいらないかも！？
    // 今後にProjectの大元を管理することも含めておいておく
    public static function store_projects_data($project_name_and_towns,$place){

         foreach($project_name_and_towns as $project_name=>$date_town_sets){
            // projectsテーブルに挿入(*projectsテーブル自体がなくなる可能性はあり)
            self::insert_projects_table($date_town_sets,$project_name,$place);

            // 配布予定テーブルに挿入(プロジェクトIdを取得する必要があるため、前項と別々に行う)
            self::insert_distribution_plans_table($date_town_sets,$project_name,$place);

        }

    }

    // projectsテーブルに挿入
    // projectsテーブル自体がなくなる可能性はあり
    public static function insert_projects_table($date_town_sets,$project_name,$place_id){

        DB::transaction(function()use($project_name,$date_town_sets,$place_id){

                // 更新か作成かの選択(案件の開始が同じプロジェクト名の締め切りより１ヶ月経過していない時は「同案件」とみなすので追加せず開始と終了を更新する)

                // 案件のstart_dateの中で最も早いものが、end_dateの１ヶ月以内かどうか()
                if(ProjectHelpers::need_user_confirm($project_name,$date_town_sets)){
                    // １ヶ月以上空いている(check時にどうするかが決定されているので、その番号を取得)


                }else{
                    // ①存在しない②存在しても1ヶ月以上なのでアップザートの処理を行う
                    self::automatic_upsert_to_projects($project_name,$date_town_sets,$place_id);
                }
        });
    }

    // 配布データに町目だけ入れる
    public static function insert_distribution_plans_table($date_town_sets,$project_name,$place_id){
        DB::transaction(function()use($project_name,$date_town_sets,$place_id){

            // 存在すれば案件のId
            $project_id=ProjectHelpers::get_latest_project_id_from_name($project_name);

            foreach($date_town_sets as $each_sets){
                $city=$each_sets["city"]; $town=$each_sets["town"];
                $address_id=AddressHelpers::get_id_from_city_and_town($city,$town);
                $start_date=$each_sets["start_date"];
                $end_date=$each_sets["end_date"];

                // 同じプロジェクトIdとtownがすでに存在するかどうかで自由に選択できるかを決定
                if(DistributionPlanHelpers::data_is_exists($project_id,$address_id)){
                    // ユーザーの選択によって新規登録か更新かを決定
                    self::upsert_after_confirmation_to_plans($project_name,$start_date,$end_date,$address_id,$place_id);

                }else{
                    // 自動挿入
                  self::automatic_insert_to_plans($project_name,$start_date,$end_date,$address_id,$place_id);
                }




            }

        });
    }

    // projectsテーブルを自動的にアップザート(自動更新期限内にあるor名前が重なる案件がない)
    public static function automatic_upsert_to_projects($project_name,$date_town_sets,$place_id){
        Project::updateOrCreate(
            //対象となるキー。ここで同じものがあればupdate(アップザートの要領)
            ["project_name"=>$project_name],
            //作成もしくは更新する
            [
                "created_by"=>Auth::user()->id,
                "start_date"=>min(array_column($date_town_sets,"start_date")),
                "end_date"=>max(array_column($date_town_sets,"end_date")),
                "place_id"=>$place_id
                //同案件フラグナンバーは存在していればそのまま、新規作成のものは1になる
                //saveは自動で行われる
            ]
            );
    }

    // projectsテーブルを確認した値に応じてアップザート(自動更新期限切れかつ名前が重なる案件が存在）
    public static function upsert_after_confirmation_to_projects($project_name,$date_town_sets,$place_id){
        // フラグの受け取り後に作用

    }

    // distribution_plansテーブルに自動的に挿入(案件名と町目が重なる案件がない)
    public static function automatic_insert_to_plans($project_name,$start_date,$end_date, $address_id,$place_id){

                $distribution_plans=new DistributionPlan();
                // 誰が登録したか
                $distribution_plans->created_by=Auth::user()->id;
                //プロジェクトのId
                $distribution_plans->project_id=ProjectHelpers::get_latest_project_id_from_name($project_name);
                // 営業所Id
                $distribution_plans->place_id=$place_id;
                // 期限
                $distribution_plans->start_date=$start_date;
                $distribution_plans->end_date=$end_date;
                // 住所
                $distribution_plans->address_id=$address_id;
                // 備考(案件担当から)
                $distribution_plans->remark_from_operator="";
                $distribution_plans->save();
    }


    // distribution_plansテーブルを確認した値に応じてアップザート(町目分割可能性ありの時）
    public static function upsert_after_confirmation_to_plans($project_name,$start_date,$end_date,$address_id,$place_id){
        // フラグの受け取り後に作用

    }


}
