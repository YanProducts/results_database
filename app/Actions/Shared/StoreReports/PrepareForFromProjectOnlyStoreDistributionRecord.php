<?php

namespace App\Actions\Shared\StoreReports;

use App\Exceptions\BusinessException;
use App\Models\DistributionPlan;
use App\Support\Common\ModelHelpers\DistributionProjectAssignmentHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Support\Common\ModelHelpers\ProjectPlannedCountHelpers;
use Carbon\Carbon;

// 案件のみから提出された版の投稿→保存に向けたデータ取得の流れ(配列で返す)
class PrepareForFromProjectOnlyStoreDistributionRecord{

    // 保存に必要なデータ(配布済に変更するassignidと保存用配列)を取得
    public static function prepare_for_store($date,$report_data,$staff_id,$from_edit=false){

        // 報告書提出に関するSQLの取得
        [$main_plan_models,$sub_plan_sets]=self::get_sql_data_for_insert($report_data);

        // statusの変更に関するassignIdの取得(sqlも使用)
        // 内部でエラー除去
        $assign_project_ids=self::get_assign_project_ids($staff_id,$date,$main_plan_models);

        // 挿入用のデータの取得
        $data_to_store_array=self::get_insert_data($staff_id,$date,$report_data,$main_plan_models,$sub_plan_sets);

        //assign_Idは案件版で取得
        return [$assign_project_ids,$data_to_store_array];
    }


    // 報告書提出に関するSQLの取得
    public static function get_sql_data_for_insert($report_data){
        // report_dataはplan_idが入っている(パラメータはidentifier、メイン案件のみ)
        // 上記のidを持つplan(つまりメイン案件)と、そのメイン案件をmain_idに持つ(identifierはメイン案件のみのplan_id)サブ案件を取得
        $main_plan_ids=array_column($report_data,"identifier");
        $main_plan_models=DistributionPlan::findOrFail($main_plan_ids)->keyBy("id"); //複数で取得したfindはコレクションが返る //findOrFailでは同様に存在確認でエラーに投げることも可能なので、不正なプログラムや僅かな隙での更新にも対応
        //サブ案件はmain_idでgroupBy
        $sub_plan_sets=DistributionPlan::whereIn("main_id",$main_plan_ids)->get()->groupBy("main_id");
        return [$main_plan_models,$sub_plan_sets];
    }

    // statusの変更に関するassignIdの取得(sqlも使用)
    // 内部でエラー除去
    public static function get_assign_project_ids($staff_id,$date,$main_plan_models){
        // メイン案件のコレクションからproject_idとround_numberを取得groupByによって取得し、project_planned_idを取得し、それに対応するproject版のassignmentsの取得

        $place_id=FieldStaffListHelpers::get_place_id_by_user($staff_id);//スタッフから営業所の取得

        $project_round_number_place_id_sets=$main_plan_models->groupBy(["project_id","round_number"])->map(fn($each_project_set,$project_id)=>$each_project_set->map(fn($each_project_with_round_number,$round_number)=>["project_id"=>$project_id,"round_number"=>$round_number,"place_id"=>$place_id]))->flatten(1);

        //project_idとround_numberとplace_idのセットの取得 //project_idとround_numberとplace_idが決まると、project_planned_idは一意に決まる(dateは必要ない。なぜならdateが違うとround_numberも違い、複数日案件はend_dateが記載されるだけだから)
        // 一意になったproject_planned_countsのidの取得
        $project_planned_ids=ProjectPlannedCountHelpers::get_project_planned_collection_from_project_round_number_place_queries($project_round_number_place_id_sets)->pluck("id"); //クエリに対してpluckは可能

        // 上記のproject_planned_countsのidとdateとstaff_idで一意になったdistribution_assignmentsのidを取得
        // 戻したfunctionでstatusをデータありという意味で「１」にするが、end_dateの扱い次第では、「０、１、２」で分けることもあり(そもそもstatusを分ける必要があるのかも未確定)とりあえずidを戻す
        $assign_project_ids=DistributionProjectAssignmentHelpers::get_ids_by_project_plan_ids_and_staff_and_date($staff_id,$date,$project_planned_ids);

        //project_round_number_place_id_setsと取得されたproject_planned_idsのコレクションの要素数と$assign_project_idsの要素数が等しいか
        if($project_planned_ids->count() !== $project_round_number_place_id_sets->count() || $project_planned_ids->count() !==$assign_project_ids->count()){
            throw new BusinessException("案件更新に関するエラーです");
        }

        return $assign_project_ids;
    }

    // reportデータに挿入する内容の取得
    public static function get_insert_data($staff_id,$date,$report_data,$main_plan_models,$sub_plan_sets){
        $now=Carbon::now();

        $base_array=[
            "created_at"=>$now,
            "updated_at"=>$now,
            "distribution_date"=>$date,
            "staff_id"=>$staff_id,
        ];

        // 最終的に"distribution_date","distribution_count","address_id","project_id","staff_id","remark","plan_id"を入れ子の返す
        // これはメインのみ
        $data_to_store_array=[];
        foreach($report_data as $each_data){

                // // editの場合、mainCountのデータがない場合は飛ばす
                // むしろeditにこれは必要か？後で仕組みを見るべき
                // if($from_edit && !array_key_exists("mainCount",$each_data)){
                //     continue;
                // }


            $plan_id=$each_data["identifier"];
            $plan_model=$main_plan_models->get($each_data["identifier"]);
            //クエリビルダではなくコレクションにgetを行えば、keyに対応するデータが返る
            //keyBy("id")で先にデータベースから抽出したコレクションから取得
            //上記定義でidentifierに対応するidは必ず存在(定義からこの式に到達するまでの間に更新が行われている場合はplan_idはsqlでは変更されてしまうが、その場合は外部キー制約で挿入時にリレーションエラーが生じてくれる)

            // 一方でplanのaddressやprojectの変更の差異に対してはエラーが生じないから要注意（未対策）

            $data_to_store_array[]=[
                ...$base_array,
                // 初期投稿で併配のみの場合は空欄になっているので「0」を記入
                "distribution_count"=>$each_data["mainCount"] ?? 0,
                "address_id"=>$plan_model->address_id,
                "project_id"=>$plan_model->project_id,
                "remarks"=>"",
                "plan_id"=>$plan_id
            ];

            // 上記のplan_idをmain_idに持つサブ案件をarrayに加える
            // 報告書上で上記に対応する併配案件$each_data["subData"]に入ってる
            $data_to_store_array=self::add_sub_insert_data($base_array,$plan_id,$sub_plan_sets,$each_data["subData"],$data_to_store_array);

            }
        return $data_to_store_array;
    }

    // サブ案件の挿入用データを加える
    public static function add_sub_insert_data($base_array,$plan_id,$sub_plan_sets,$sub_data_by_the_main,$data_to_store_array){
            foreach($sub_data_by_the_main  as $each_sub_set){

                //sub_data_by_the_mainは単配だと空になるので、併配の場合のみここに到達
                // 併配案件が見つからない場合(plan_idのキーがない、plan_idに対応するコレクションが空)、project_idが存在しない時、project_idを途中で不正に入れられた場合はエラー
                // 同時に、このループ内で該当する併配案件(町目と併配案件が決定している)のplanのモデルを取得(sub_plan_in_the_project)
                if((!$sub_plan_sets->has($plan_id)) ||  empty($sub_project_id=$each_sub_set["project_id"]) || empty($sub_plan_in_the_project=$sub_plan_sets[$plan_id]->where("project_id",$sub_project_id)->first())
                    ){throw new BusinessException("併配案件取得でエラーが生じました");}

                // 途中で消して空白になっている場合は次のループへ(厳密比較でないと、0も""で判定する可能性あり)
                if (in_array($each_sub_set["subCount"], ["", null], true)) {
                    continue;
                }

                $data_to_store_array[]=[
                    ...$base_array,
                    "distribution_count"=>$each_sub_set["subCount"],
                    "address_id"=>$sub_plan_in_the_project->address_id,
                    "project_id"=>$sub_project_id,
                    "remarks"=>"",
                    "plan_id"=>$sub_plan_in_the_project->id
                ];
            }
        return $data_to_store_array;
    }
}

