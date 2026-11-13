<?php

namespace App\Actions\Shared\StoreReports;

use App\Exceptions\BusinessException;
use App\Models\DistributionPlan;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use Carbon\Carbon;

// 案件のみから提出された版の投稿→保存に向けたデータ取得の流れ(配列で返す)
class PrepareForFromProjectOnlyStoreDistributionRecord{

    // 保存に必要なデータ(配布済に変更するassignidと保存用配列)を取得
    public static function prepare_for_store($date,$report_data,$staff_id,$from_edit=false){

        // report_dataはplan_idが入っている(パラメータはidentifier、メイン案件のみ)
        // 上記のidを持つplan(つまりメイン案件)と、そのメイン案件をmain_idに持つ(identifierはメイン案件のみのplan_id)サブ案件を取得
        $main_plan_ids=array_column($report_data,"identifier");
        $main_plan_models=DistributionPlan::findOrFail($main_plan_ids); //複数で取得したfindはコレクションが返る //findOrFailでは同様に存在確認でエラーに投げることも可能なので、不正なプログラムや僅かな隙での更新にも対応
        //サブ案件はmain_idでgroupBy
        $sub_plan_sets=DistributionPlan::whereIn("main_id",$main_plan_ids)->get()->groupBy("main_id");




        // メイン案件のコレクションからproject_idとround_numberを取得groupByによって取得し、project_planned_idを取得し、それに対応するproject版のassignmentsの取得

        $place_id=FieldStaffListHelpers::get_place_id_by_user($staff_id);//スタッフから営業所の取得
        $project_round_number_place_id_sets=$main_plan_models->groupBy(["project_id","round_number"])->keys()->map(fn($each_project_round_number_set)=>[...$each_project_round_number_set,"place_id"=>$place_id]); //project_idとround_numberとplace_idのセットの取得 //project_idとround_numberとplace_idが決まると、project_planned_idは一意に決まる

        



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
            $plan_model=$main_plan_models->where("id",$each_data["identifier"])->first();//findOrFailで先にデータベースから抽出したコレクションから取得 //上記定義でidentifierに対応するidは必ず存在(定義からこの式に到達するまでの間に更新が行われている場合はplan_idはsqlでは変更されてしまうが、その場合は外部キー制約で挿入時にリレーションエラーが生じてくれる)


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

            // 上記のplan_idをmain_idに持つサブ案件
            // 報告書上で上記に対応する併配案件$each_data["subData"]に入ってる
            $sub_data_by_the_main=$each_data["subData"];
            foreach($sub_data_by_the_main  as $each_sub_set){

                //sub_data_by_the_mainは単配だと空になるので、併配の場合のみここに到達
                // 併配案件が見つからない場合(plan_idのキーがない、plan_idに対応するコレクションが空)、project_idが存在しない時、project_idを途中で不正に入れられた場合はエラー
                // 同時に、このループ内で該当する併配案件(町目と併配案件が決定している)のplanのモデルを取得(sub_plan_in_the_project)
                if((!$sub_plan_sets->has($plan_id)) ||  empty($sub_project_id=$each_sub_set["project_id"]) || empty($sub_plan_in_the_project=$sub_plan_sets[$plan_id]->where("project_id",$sub_project_id)->first())
                    ){throw new BusinessException("併配案件取得でエラーが生じました");}

                // 途中で消して空白になっている場合は次のループへ(厳密比較でないと、0も""で判定する可能性あり)
                if($each_sub_set["subCount"]===""){
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
        }

        //assign_Idは案件版で取得

        return ["",$data_to_store_array];
    }

}
