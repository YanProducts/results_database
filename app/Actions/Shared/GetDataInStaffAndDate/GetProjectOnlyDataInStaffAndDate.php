<?php
// その日のそのスタッフにおける、割り当てられたデータ
namespace App\Actions\Shared\GetDataInStaffAndDate;

use App\Models\DistributionPlan;
use App\Models\DistributionRecord;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\DistributionProjectAssignmentHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Support\Common\ModelHelpers\ProjectPlannedCountHelpers;
use Illuminate\Support\Facades\Log;

class GetProjectOnlyDataInStaffAndDate{

    // データの取得(そのスタッフの予定データを基軸に、編集の場合は結果データも取得)
    public static function get_assigned_data($staff_id,$date_sets,$is_edit=false,$record_data=null){

        // N+1防止のため、SQLデータを先に取得
        [$submitted_dates,$project_only_assigned_data,$project_plans_sets,$main_plan_sets,$sub_plan_sets,$project_names_sets,$existed_address_sets]=self::get_data_in_sql($staff_id,$date_sets,$is_edit);

        // そのスタッフの報告書用のデータ(dateをキーに:メイン案件名がサブキー:[その下位はオブジェクトの配列。addressId,addressName,planId,subSets{"projectName","planId"}]//併配も含めた案件セット})
        foreach(array_keys($date_sets) as $date){

            // // その日が配布済みに入っている場合も、案件と通常の両方でassignがあった場合にエラーを出すため、continueせずに捕捉する

            // // phpはスコープ内宣言でOK
            // // 結果と予定で仕分ける
            $return_sets[$date]=self::get_data_by_date($date,$project_only_assigned_data,$project_plans_sets,$main_plan_sets,$sub_plan_sets,$project_names_sets,$existed_address_sets,$is_edit,$record_data);

        }


        // 営業所からの場合など編集の場合はsubmitted_dateは空、結果の場合はfrom_simple_flagは空
        return [$return_sets ?? [],  $submitted_dates ];
    }

    // N+1防止のために一括取得(営業所長からの場合もあり、結果取得の場合も基本の構造は同じ)
    public static function get_data_in_sql($staff_id,$date_sets,$is_edit=false){

            // 営業所Idの取得
            $place_id=FieldStaffListHelpers::get_place_name_by_user($staff_id);

            // // そのスタッフの、期間内を含む案件の、配布済のデータを含むdateの取得(planとassignのSQLデータは「期間」で取得しているため期間全取得、最後の配列変換の際に影響）
            // // 編集の場合は実行されない(配布データがあるかどうかに関わらず予定は取得する必要があるため：結果テーブルだと行っていないor書き忘れの町目のデータがないため)
            $submitted_dates=!$is_edit ? DistributionRecord::where("staff_id",$staff_id)->whereIn("distribution_date",array_keys($date_sets))->pluck("distribution_date")->unique()->values() : collect();

            // そのスタッフで、期間内に、既に案件のみの割り当てに既になされている案件予定割当データのproject_planned_idのコレクション取得(必然的にmain案件のみになる)
            $project_only_assigned_data=DistributionProjectAssignmentHelpers::get_assigns_by_staff_and_date($staff_id,array_keys($date_sets));

            // 上記に相当するproject_planned_countsのテーブルにおけるidから、project_idとround_numberを返すために使用する（dateなども使用する可能性に備えて全取得）。 idで検索ができるようにする(主にdistribution_plansを逆引きするのに使用)
            $project_plans_sets=ProjectPlannedCountHelpers::get_project_id_and_round_number_for_search_plans($project_only_assigned_data->pluck("project_plan_id"),$place_id);

            // メインで使用するプロジェクトの名前のセット(まとめてできるかも)
            // $main_project_names_sets=Project::find($project_plans_sets->pluck("project_id"))->pluck("project_name","id");

            // 上記に相当するdistribution_planのデータの取得(同様にメイン案件のみ)
            $main_plan_sets=DistributionPlanHelpers::get_plan_data_from_project_planned_table_data($project_plans_sets,$place_id);

            // サブ案件のplanのコレクション
            //報告書の欄に載せるために、その日にassignされたmain案件に限らず、その日に営業所にきた案件のidは取得が必要
            $sub_plan_sets=DistributionPlan::select("id","project_id","round_number","address_id","main_id")->whereIn("main_id",$main_plan_sets->pluck("id"))->get();

            // プロジェクトの名前セット(id=>プロジェクト名) //表示用のためround_number無視(後で表示用の値は設定)
            $project_names_sets=ProjectHelpers::get_project_names_key_by_id(array_unique([...$project_plans_sets->pluck("project_id"),...$sub_plan_sets->pluck("project_id")]));

            // 住所のキー=>市町目&世帯数のセットで返す(メイン案件のみで網羅できる)
            $existed_address_sets=AddressHelpers::get_address_name_and_household_set_arrays_key_by_id(array_unique([...$main_plan_sets->pluck("address_id")]));

            return[$submitted_dates,$project_only_assigned_data,$project_plans_sets,$main_plan_sets,$sub_plan_sets,$project_names_sets,$existed_address_sets];
    }


    // 日毎の処理 //すでに日は限定
    // その日、そのスタッフ割り当てられているplan限定
    public static function get_data_by_date($date,$project_only_assigned_data,$project_plans_sets,$main_plan_sets,$sub_plan_sets,$project_names_sets,$existed_address_sets,$is_edit,$record_data){

        // その日に自分に割り当てられているメイン案件のplan($project_only_assigned_data)から、project_nameとround_numberをgroupByして名前を取得(いくつかのコレクションを辿る)。対象のものを限定し(place_idは限定済のため案件は一意に決定)、MapでgroupByして自分でソートできるようにする。
        // 最終系はdate=>割り当てられた案件(planIdで提出するので名前で良い)=>[project_sets=>併配も含めた案件idと名前のセット(キーには"id"をつけた後にid名、名前はプロジェクトのround_numberつき表示名)、each_data=>map_number=>[address_id,address_name,"address_name_when_big_media",household,counts(edit用),sub_sets=>[]*assign_idは存在しない代わりに、上位にproject_onlyからきたことを記すフラグをつける]]

        // その日に案件ごとに割り当てられたメイン案件から、メイン案件のproject_planned_countのidのセットを求め、そのidセットを含むproject_plannd_countsのコレクションを取得
        $project_plans_sets_in_the_date=$project_plans_sets->only($project_only_assigned_data->where("date",$date)->pluck("project_plan_id")->toArray());
        // その日のproject_planned_countのデータから、最終的にはproject_nameとround_numberを持つplanを求める
        //その前に表示用のprojectNameを求めるために、project_nameでgroupByを行う(project_nameでgroupByしたとき、その要素がいくつあるかがround_numberの個数)
        $return_sets=[];
        foreach($project_plans_sets_in_the_date->groupBy(["project_id","round_number"]) as $project_id=>$each_plan_data_by_project){
            // その日のproject_idを持つ案件数によって配列の個数が決定
            $round_number_index=0;

         foreach($each_plan_data_by_project as $round_number=>$each_plan_data_by_project_and_round_number){

                // そのproject_idとround_numberを持つplanの取得=すなわちメイン案件(=住所や世帯などにも必要)
                $main_plan_sets_in_the_project=$main_plan_sets->where("project_id",$project_id)->where("round_number",$round_number);

                // 上記をmain_idに持つ案件の取得＝すなわちサブ案件
                $sub_plan_sets_in_the_project=$sub_plan_sets->whereIn("main_id",$main_plan_sets_in_the_project->pluck("id"));

                // [project_sets=>併配も含めた案件idと名前のセット(キーには"id"をつけた後にid名、名前はプロジェクトのround_numberつき表示名)、each_data=>map_number=>[address_id,address_name,"address_name_when_big_media",household,counts(edit用),sub_sets=>[]*assign_idは存在しない代わりに、上位にproject_onlyからきたことを記すフラグをつける]]

                // このプロジェクトで使用する住所セット
                $address_sets_in_the_project=collect($existed_address_sets)->whereIn("id",$main_plan_sets_in_the_project->pluck("address_id"));

                // このプロジェクトの内部で違う市で同名の町が存在するか
                $duplicate_town_names=$address_sets_in_the_project->pluck("town")->duplicates();

                // ここはプロジェクト名に直すこと
                $return_sets[($project_names_sets[$project_id] ?? "不明").":".$round_number_index]=
                [
                    // project_name_setsはid=>プロジェクト名。そこからこの案件のprojectを抽出
                    "project_set"=>($project_names_sets->only([$project_id,...$sub_plan_sets_in_the_project->pluck("project_id")]))->mapWithKeys(fn($each_project_name,$each_project_id)=>["id".$each_project_id=>$each_project_name]),
                    "each_data"=>$main_plan_sets_in_the_project->groupBy("map_number")->mapWithKeys(function($each_data_sets_by_map_number,$map_number)use($address_sets_in_the_project,$is_edit,$duplicate_town_names,$record_data,$sub_plan_sets_in_the_project){
                        return[$map_number=>
                            $each_data_sets_by_map_number->map(function($each_plan_data)use($address_sets_in_the_project,$is_edit,$duplicate_town_names,$record_data,$sub_plan_sets_in_the_project){
                                $address_id=$each_plan_data->address_id; //住所Id(複数回取得するので先に取得)
                                //その町目のデータ
                                $town_data=$address_sets_in_the_project[$address_id];
                                return[
                                "plan_id"=>$plan_id=$each_plan_data->id, //通常盤ではassign_idだが、それ自体が存在しない
                                "address_id"=>$address_id,
                                "address_name"=>$duplicate_town_names->contains($town_name=$town_data["town"]) ? $town_name."\n(".$town_data["city"].")" : $town_name,
                                "address_name_when_big_media"=>$town_data["city"].$town_data["town"],
                                "household"=>$town_data["household"],
                                // 編集用の合計(record_dataは引き渡しの段階でdateとstaffでfilter済)
                                "counts"=>$is_edit ? $record_data->where("plan_id",$plan_id)->value("distribution_count") :"",

                                "sub_sets"=>$sub_plan_sets_in_the_project->isNotEmpty() ? ($is_edit ?
                                // 編集の時(その町目のplanのデータがあるかどうかで取得)
                                ($sub_plan_sets_in_the_project->pluck("project_id","id"))->mapWithKeys(fn($sub_project_id,$id)=>[
                                    $sub_project_id=>$record_data->where("plan_id",$id)->value("distribution_count") //その併配案件の結果(日付とスタッフでは絞りこみ済み)
                                ]):
                                // 併配案件のプロジェクトidを返す
                                $sub_plan_sets_in_the_project->where("main_id",$each_plan_data->id)->pluck("project_id")
                                ):[] //単配の場合;
                            ];
                            })
                        ];
                    }),//map_numberでのループ
                ]; //projectごとのデータの取得の配列
            $round_number_index++;
         }//round_numberのforeachループ
        }//projectのループ
      return $return_sets;
  }

}
