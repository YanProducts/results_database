<?php
// その日のそのスタッフにおける、割り当てられたデータ
namespace App\Actions\Shared;

use App\Models\DistributionAssignment;
use App\Models\DistributionPlan;
use App\Models\DistributionRecord;
use App\Models\FieldStaffList;
use App\Models\Project;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use App\Support\FieldStaff\ChangeProjectNameForView;
use App\Support\Common\GetDateRangeQuery;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\DistributionProjectAssignmentHelpers;
use App\Support\Common\ModelHelpers\ProjectPlannedCountHelpers;
use Illuminate\Support\Facades\Log;

class GetProjectOnlyDataInStaffAndDate{

    // データの取得(そのスタッフの予定データを基軸に、編集の場合は結果データも取得)
    public static function get_assigned_data($staff_id,$date_sets,$is_edit=false,$record_data=null){

        // N+1防止のため、SQLデータを先に取得
        [$submitted_dates,$project_only_assigned_data,$project_plans_sets,$main_plan_sets,$sub_plan_sets,$project_names_sets,$existed_address_sets,$existed_projects_sets]=self::get_data_in_sql($staff_id,$date_sets,$is_edit);

        // そのスタッフの報告書用のデータ(dateをキーに:メイン案件名がサブキー:[その下位はオブジェクトの配列。addressId,addressName,planId,subSets{"projectName","planId"}]//併配も含めた案件セット})
        foreach(array_keys($date_sets) as $date){

            // // その日が配布済みに入っていたら報告リストには載せない(編集側の場合は、そもそもsubmitted_datesを入れていない)
            if(!empty($submitted_dates) && $submitted_dates->contains($date)){
               $return_sets[$date]=null;
                continue;
            }

            // // phpはスコープ内宣言でOK
            // // 結果と予定で仕分ける
            $return_sets[$date]=self::get_data_by_date($date,$project_only_assigned_data,$project_plans_sets,$main_plan_sets,$sub_plan_sets,$project_names_sets,$existed_address_sets,$existed_projects_sets,$is_edit);

        }


        // 営業所からの場合など編集の場合はsubmitted_dateは空、結果の場合はfrom_simple_flagは空
        return [$return_sets ?? [],  $submitted_dates ];
    }

    // N+1防止のために一括取得(営業所長からの場合もあり、結果取得の場合も基本の構造は同じ)
    public static function get_data_in_sql($staff_id,$date_sets,$is_edit=false){

            // 営業所Idの取得
            $place_id=FieldStaffList::get_place_name_by_user($staff_id);

            // // そのスタッフの、期間内を含む案件の、配布済のデータを含むdateの取得(planとassignのSQLデータは「期間」で取得しているため期間全取得、最後の配列変換の際に影響）
            // // 編集の場合は実行されない(配布データがあるかどうかに関わらず予定は取得する必要があるため：結果テーブルだと行っていないor書き忘れの町目のデータがないため)
            $submitted_dates=!$is_edit ? DistributionRecord::where("staff_id",$staff_id)->whereIn("distribution_date",array_keys($date_sets))->pluck("distribution_date")->unique()->values() : collect();

            // そのスタッフで、期間内に、既に案件のみの割り当てに既になされている案件予定割当データのproject_planned_idのコレクション取得(必然的にmain案件のみになる)
            $project_only_assigned_data=DistributionProjectAssignmentHelpers::get_assigns_by_staff_and_date($staff_id,$date_sets);

            // 上記に相当するproject_planned_countsのテーブルにおけるidから、project_idとround_numberを返すために使用する（dateなども使用する可能性に備えて全取得）。 idで検索ができるようにする(主にdistribution_plansを逆引きするのに使用)
            $project_plans_sets=ProjectPlannedCountHelpers::get_project_id_and_round_number_for_search_plans($project_only_assigned_data->pluck("project_planned_id"),$place_id);

            // メインで使用するプロジェクトの名前のセット(まとめてできるかも)
            // $main_project_names_sets=Project::find($project_plans_sets->pluck("project_id"))->pluck("project_name","id");

            // 上記に相当するdistribution_planのデータの取得(同様にメイン案件のみ)
            $main_plan_sets=DistributionPlanHelpers::get_plan_data_from_project_planned_table_data($project_plans_sets,$place_id);

            // サブ案件のplanのコレクション
            //報告書の欄に載せるために、その日にassignされたmain案件に限らず、その日に営業所にきた案件のidは取得が必要
            $sub_plan_sets=DistributionPlan::select("id","project_id","round_number","address_id","main_id")->whereIn("main_id",$main_plan_sets->pluck("id"))->get();

            // プロジェクトの名前セット(id=>プロジェクト名) //表示用のためround_number無視
            $project_names_sets=ProjectHelpers::get_project_names_key_by_id([...$project_plans_sets->pluck("project_id"),...$sub_plan_sets->pluck("project_id")]);

            // 住所のキー=>市町目&世帯数のセットで返す(メイン案件のみで網羅できる)
            $existed_address_sets=AddressHelpers::get_address_name_and_household_set_arrays_key_by_id(array_unique([...$main_plan_sets->pluck("address_id")]));

            return[$submitted_dates,$project_only_assigned_data,$project_plans_sets,$main_plan_sets,$sub_plan_sets,$project_names_sets,$existed_address_sets];
    }

    // // メイン案件のデータを返す(main_plan_setsはexisted_collectionsと同じ。assignの段階でメインに絞っているため)
    // public static function get_main_projects_data_in_the_day($main_data_in_date,$main_plan_sets){
    //         // その日の「assignのid=>planのid」と対応した連想配列を取得(値だけみたらmain_plan_ids)。条件としてmain案件のもののみを取得する
    //         $main_plan_ids=collect($main_data_in_date->mapWithKeys(fn($each_data)=>[$each_data->id=>$each_data->plan_id]))->filter(fn($each_plan_id,$each_assign_id)=>$main_plan_sets->where("id",$each_plan_id)->isNotEmpty());

    //         // その日のplan
    //         $plan_collections=$main_plan_sets->whereIn("id",$main_plan_ids);

    //         // メイン案件のIdセットと、メイン案件のIdセットをId名=>そのセットの入れ子配列を返す（同じ案件名があった時のため、same_project_flag,round_nameも条件につけ,表示用にmap_numberもつける）
    //         return[$main_plan_ids,$plan_collections->groupBy(["project_id","round_number","map_number"])];
    // }

    // // サブ案件も含めたプロジェクトのセット(キーがid&idの数字=>値がプロジェクトのID)
    // // mapでは配布外でも対応した報告書(mapになくとも全体表にあることが必要なため、案件に合わせる)
    // public static function get_project_names_in_the_report($all_distribution_plans,$main_project_id,$round_number,$existed_projects_sets,$main_project_name){

    //         $all_id_lists_in_the_main_projects=$all_distribution_plans->where("project_id",$main_project_id)->where("round_number",$round_number)->where("main_id",null)->pluck("id");

    //         $all_sub_lists_in_the_projects=$all_distribution_plans->whereIn("main_id",$all_id_lists_in_the_main_projects)->pluck("project_id")->unique()->mapWithKeys(fn($each_project_id)=>["id".$each_project_id=>$existed_projects_sets[$each_project_id]]);

    //         return [
    //             "id".$main_project_id=>$main_project_name,
    //             ... $all_sub_lists_in_the_projects
    //         ];
    // }

    // // 案件セットが得られた後で、それを表示用に並べ替える
    // public static function format_project_sets_key_by_project_for_view($main_plans_grouped_by_project_names,$all_distribution_plans,$main_plan_ids_in_the_day,$existed_address_sets,$sub_sets_in_the_day,$existed_projects_sets,$record_data,$is_edit){

    //    foreach($main_plans_grouped_by_project_names as $main_project_id=>$main_project_data_sets_with_round_number){

    //             // メイン案件はidで仕分けされているので、その案件名を返す
    //             $main_project_name=ProjectHelpers::get_project_name_from_id($main_project_id);

    //                 // round_numberはSQLの番号ではなく今回のインデックスで表示
    //                 $round_index=1;
    //                 foreach($main_project_data_sets_with_round_number as $round_number=>$main_project_data_sets_with_map_number){
    //                     // そのメイン案件の同時期のもので、今回営業所にきたメイン案件あるか(round_numberの長さ)
    //                     $round_length=count($main_project_data_sets_with_round_number);
    //                     // ここのmain_projectは表示用なので変えてもOKかも？round_numberなどと連動して
    //                     $main_project_key_name_for_view=ChangeProjectNameForView::get_project_name_for_view($main_project_name,$round_index,$round_length);
    //                    // round_indexを1つたす
    //                     $round_index++;

    //                     // サブ案件も含めたプロジェクトのセット(キーがid&idの数字=>値がプロジェクトのID)
    //                     // mapでは配布外でも対応した報告書(mapになくとも全体表にあることが必要なため、案件に合わせる)
    //                      $return_sets_by_date[$main_project_key_name_for_view]["project_set"]=self::get_project_names_in_the_report($all_distribution_plans,$main_project_id,$round_number,$existed_projects_sets,$main_project_name);

    //                     foreach($main_project_data_sets_with_map_number as $map_number=>$main_project_data_sets_by_map_number){

    //                     // 町名が重複している町目
    //                     $duplicate_town_names=(collect($existed_address_sets)->pluck("town"))->duplicates();

    //                         // 必要な世帯数データを抽出する(集合や戸建など)
    //                         foreach($main_project_data_sets_by_map_number as $main_project_data){
    //                             $main_plan_id=$main_project_data->id; //そのmainplanのId(複数回使用するので取得)
    //                             $address_id=$main_project_data->address_id; //住所Id(複数回取得するので先に取得)
    //                             //その町目のデータ
    //                             $town_data_in_id=$existed_address_sets[$address_id];

    //                             $return_sets_by_date[$main_project_key_name_for_view]["each_data"][$map_number][]=[
    //                                 // その日その人に振られたprojectIdは一意に決まり、すでに日と人とではfilterにかけられているので、plan_idからassign_idは取得可能
    //                                 "assign_id"=>$main_plan_ids_in_the_day->search($main_plan_id),
    //                                 //  住所のid
    //                                 "address_id"=>$address_id,
    //                                 //  モバイル用の住所の名前(町名が基本、重なっていたら(\n市名))
    //                                 "address_name"=>$duplicate_town_names->contains($town_name=$town_data_in_id["town"]) ? $town_name."\n(".$town_data_in_id["city"].")" : $town_name,
    //                                 // 横幅が長い用の住所の名前
    //                                 "address_name_when_big_media"=>$town_data_in_id["city"].$town_data_in_id["town"],
    //                                 // 世帯数(後日、場合わけ必要)
    //                                 "household"=>$town_data_in_id["household"],

    //                                 // 編集の場合はメインの配布数(日とスタッフでは絞り済み。plan_idで絞る)
    //                                 "counts" => $is_edit ? $record_data->where("plan_id",$main_plan_id)->value("distribution_count"): "",

    //                                 // 併配がある案件のセット
    //                                 // 編集の場合は[[project_id=>"",counts=>""]]のセットにする
    //                                 "sub_sets"=>isset($sub_sets_in_the_day[$main_plan_id]) ? //単配の場合の除外
    //                                 ($is_edit ?


    //                                 ($sub_sets_in_the_day[$main_plan_id]->pluck("project_id","id"))->mapWithKeys(fn($sub_project_name,$id)=>[
    //                                     $sub_project_name=>$record_data->where("plan_id",$id)->value("distribution_count") //その併配案件の結果(日付とスタッフでは絞りこみ済み)
    //                                 ])




    //                                 : ($sub_sets_in_the_day[$main_plan_id]->pluck("project_id"))) : [],
    //                             ];
    //                         } //map_numberごと
    //                     }//round_numberごと
    //             }
    //         }
    //         return $return_sets_by_date ?? [];
    // }


    // 日毎の処理 //すでに日は限定
    // その日、そのスタッフ割り当てられているplan限定
    public static function get_data_by_date($date,$project_only_assigned_data,$project_plans_sets,$main_plan_sets,$sub_plan_sets,$project_names_sets,$existed_address_sets,$is_edit){

    // その日に自分に割り当てられているメイン案件のplan($project_only_assigned_data)から、project_nameとround_numberをgroupByして名前を取得(いくつかのコレクションを辿る)。対象のものを限定し(place_idは限定済のため案件は一意に決定)、MapでgroupByして自分でソートできるようにする。
    // 最終系はdate=>割り当てられた案件(planIdで提出するので名前で良い)=>[project_sets=>併配も含めた案件idと名前のセット(キーには"id"をつけた後にid名、名前はプロジェクトのround_numberつき表示名)、each_data=>map_number=>[address_id,address_name,"address_name_when_big_media",household,counts(edit用),sub_sets=>[]*assign_idは存在しない代わりに、上位にproject_onlyからきたことを記すフラグをつける]]


    // その日に案件ごとに割り当てられたメイン案件から、メイン案件のproject_planned_countのidのセットを求め、そのidセットを含むproject_plannd_countsのコレクションを取得
    $project_plans_sets_in_the_date=$project_plans_sets->only($project_only_assigned_data->where("date",$date)->pluck("project_plan_id"));

    // その日のproject_planned_countのデータから、最終的にはproject_nameとround_numberを持つplanを求める
    //その前に表示用のprojectNameを求めるために、project_nameでgroupByを行う(project_nameでgroupByしたとき、その要素がいくつあるかがround_numberの個数)
    $return_sets=[];
    foreach($project_plans_sets_in_the_date->groupBy("project_id") as $project_id=>$each_plan_data_by_project){
        // その日のproject_idを持つ案件数によって配列の個数が決定
        $round_number_index=0;

        // ここはプロジェクト名に直すこと
        $return_sets[($project_names_sets[$project_id] ?? "不明").":".$round_number_index]=
        $each_plan_data_by_project->map(function($each_plan_data)use($project_id,$main_plan_sets,$sub_plan_sets,$project_names_sets,$existed_address_sets,$is_edit){

            // そのproject_idとround_numberを持つplanの取得=すなわちメイン案件
            $main_plan_sets_in_the_project=$main_plan_sets->where("project_id",$project_id)->where("round_number",$each_plan_data->round_number);

            // 上記をmain_idに持つ案件の取得＝すなわちサブ案件
            $sub_plan_sets_in_the_project=$sub_plan_sets->whereIn("main_id",$main_plan_sets_in_the_project->pluck("id"));

            // [project_sets=>併配も含めた案件idと名前のセット(キーには"id"をつけた後にid名、名前はプロジェクトのround_numberつき表示名)、each_data=>map_number=>[address_id,address_name,"address_name_when_big_media",household,counts(edit用),sub_sets=>[]*assign_idは存在しない代わりに、上位にproject_onlyからきたことを記すフラグをつける]]

            // このプロジェクトの内部で違う市で同名の町が存在するか
            $duplicate_town_names=(collect($existed_address_sets)->pluck("town"))->duplicates();

            return[
                "project_sets"=>$project_names_sets,
                "each_data"=>$main_plan_sets_in_the_project->groupBy("map_number")->mapWithKeys(function($each_data_sets_by_map_number,$map_number)use($existed_address_sets,$is_edit,$duplicate_town_names){
                    return[$map_number=>
                        $each_data_sets_by_map_number->map(function($each_plan_data)use($existed_address_sets,$is_edit,$duplicate_town_names){
                            $address_id=$each_plan_data->address_id; //住所Id(複数回取得するので先に取得)
                            //その町目のデータ
                            $town_data=$existed_address_sets[$address_id];
                            return[
                            "address_id"=>$address_id,
                            "address_name"=>$duplicate_town_names->contains($town_name=$town_data->town) ? $town_name."\n(".$town_data->city.")" : $town_name,
                            "address_name_when_big_media"=>$town_data->city.$town_data->town,
                            "household"=>$town_data->household,
                            "counts"=>$is_edit ? "合計取得せよ" :"",
                          ];
                        })
                    ];
                }),

                "sub_sets"=>$sub_plan_sets_in_the_project->isNotEmpty ? ($is_edit ?
                // 編集の時(その町目のplanのデータがあるかどうかで取得)
                ($sub_plan_sets_in_the_project->pluck("project_id","id"))->mapWithKeys(fn($sub_project_id,$id)=>[
                    $sub_project_id=>$record_data->where("plan_id",$id)->value("distribution_count") //その併配案件の結果(日付とスタッフでは絞りこみ済み)
                ]):
                // 併配案件のプロジェクトidを返す
                $sub_plan_sets_in_the_project->pluck("project_id")
                ):[] //単配の場合;

            ];
        });
        $round_number_index++;
    }



        // $project_planned_data=$project_plans_sets[$each_project_planned_id];
        // $main_plan_grouped_by_map_number=$main_plan_sets->where("project_id",$project_planned_data->project_id)->where("round_number",$project_planned_data->round_number)->groupBy("map_number");



    // 次はその日に割り当てられたメイン案件から、そもplanのidを取得し、それに相当する①メイン案件とサブ案件のidと表示用の名前の取得②住所の取得③is_editの際の値の取得




    // 割り当てされた予定の場合はその日に配布もしくはその日が期限内のassignされたデータを取得
    //         $main_data_in_date= $fetched_data->filter(fn($each_data)=>
    //             $each_data->date==$date || ($each_data->end_data && ($each_data->end_date>=$date || $each_data->date < $date)));

    //         if($main_data_in_date->isEmpty()){
    //             return [null,false];
    //         }

    //         // その日のメイン案件&same_project_flag&round_number&地図番号のIdセット(assignのid=>planのidの連想配列)、[プロジェクト名をキーにした、その日のplanのセット]の一覧
    //         [$main_plan_ids_in_the_day,$main_plans_grouped_by_project_names]=self::get_main_projects_data_in_the_day($main_data_in_date,$existed_plan_collections);

    //         // サブ案件のセット。main案件のIdをキーに、idとproject_idを返す
    //         $sub_sets_in_the_day=$sub_plan_sets->whereIn("main_id",$main_plan_ids_in_the_day)->groupBy("main_id");


    //         // ここは日毎に行うのが理想！！！！
    //         // その人がいく期間内の案件に1つでも「地図版」からきているものがあれば、報告書提出時の全町確認の処理は行わない
    //         $from_simple_flag=$main_data_in_date->contains(fn($each_data)=>$each_data["from_simple_flag"]==true);

    //         // projectName~MapnNumberのそれぞれを入れ子のキーにしたセットの一覧を返す
    //         return [self::format_project_sets_key_by_project_for_view($main_plans_grouped_by_project_names,$all_distribution_plans,$main_plan_ids_in_the_day,$existed_address_sets,$sub_sets_in_the_day,$existed_projects_sets,$record_data,$is_edit) ?? [],$from_simple_flag];
    }

}
