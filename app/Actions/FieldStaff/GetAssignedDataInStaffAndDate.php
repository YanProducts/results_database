<?php
// その日のそのスタッフにおける、割り当てられたデータ
namespace App\Actions\FieldStaff;
use App\Models\DistributionAssignment;
use App\Models\DistributionPlan;
use App\Models\FieldStaffList;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use App\Support\FieldStaff\ChangeProjectNameForView;
use App\Support\Common\GetDateRangeQuery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetAssignedDataInStaffAndDate{

    // データの取得
    public static function get_assigned_data($staff_id,$date_sets){

            // N+1防止のため、SQLデータを先に取得
            [$data_in_staff_and_date,$all_distribution_plans,$existed_plan_collections,$existed_projects_sets,$existed_address_sets,$sub_plan_collections]=self::get_data_in_sql($staff_id,$date_sets);

        // そのスタッフの報告書用のデータ(dateをキーに:メイン案件名がサブキー:[その下位はオブジェクトの配列。addressId,addressName,planId,subSets{"projectName","planId"}]//併配も含めた案件セット})

        // 日付をキーにして保存
        foreach(array_keys($date_sets) as $date){
            // phpはスコープ内宣言でOL
            $return_sets[$date]=self::get_data_by_date($date,$data_in_staff_and_date,$all_distribution_plans,$existed_plan_collections,$sub_plan_collections,$existed_projects_sets,$existed_address_sets);
        }

        return $return_sets;
    }

    // N+1防止のために一括取得
    public static function get_data_in_sql($staff_id,$date_sets){

            // 期間内&営業所あてに割り当てられている全てのplan(併配リストを添付データに合わせるため担当外も必要)
            $all_distribution_plans=GetDateRangeQuery::get_date_range_query(DistributionPlan::select("id","project_id","same_project_flag","round_number","address_id","map_number","place_id")->where("place_id",FieldStaffList::where("id",Auth::user()->authable_id)->value("place_id")),$date_sets,"start_date")
            ->get();

            // sqlに入っている、そのスタッフの、期間内を含む案件を取得(n+1防止に一括取得)。それをdateでまとめる
            $data_in_staff_and_date=GetDateRangeQuery::get_date_range_query(DistributionAssignment::where("staff_id",$staff_id),$date_sets,"date")->get();

            // プランのId
            $plan_ids=$data_in_staff_and_date->map(fn($each_data)=>$each_data->plan_id);

            // assignされた時点で、すでにメイン案件に絞られたものが送られているので、それに相当するものを取得
            $existed_plan_collections=$all_distribution_plans->whereIn("id",$plan_ids)->get();

            // サブ案件のplanのコレクション
            $sub_plan_collections=DistributionPlan::select("id","project_id","round_number","address_id","main_id")->whereIn("main_id",$plan_ids)->get();

            // 住所のキー=>市町目&世帯数のセットで返す
            $existed_address_sets=AddressHelpers::get_address_name_and_household_set_arrays_key_by_id(array_unique([...$existed_plan_collections->pluck("address_id"),...$sub_plan_collections->pluck("address_id")]));

            // 案件のキー=>(同案件フラグこみの)案件の名前のセットで返す
            $existed_projects_sets=ProjectHelpers::get_project_names_with_another_project_flag_array_key_by_id(array_unique([...$existed_plan_collections->pluck("project_id"),...$sub_plan_collections->pluck("project_id")]));


            return[$data_in_staff_and_date,$all_distribution_plans,$existed_plan_collections,$existed_projects_sets,$existed_address_sets,$sub_plan_collections];
    }

    // メイン案件のデータを返す(main_plan_collectionsはexisted_collectionsと同じ。assignの段階でメインに絞っているため)
    public static function get_main_projects_data_in_the_day($main_assigned_data_in_date,$main_plan_collections,$existed_projects_sets){
            // その日の「assignのid=>planのid」と対応した連想配列を取得(値だけみたらmain_plan_ids)。条件としてmain案件のもののみを取得する
            $main_plan_ids=collect($main_assigned_data_in_date->mapWithKeys(fn($each_data)=>[$each_data->id=>$each_data->plan_id]))->filter(fn($each_plan_id,$each_assign_id)=>$main_plan_collections->where("id",$each_plan_id)->isNotEmpty());

            // その日のplan
            $plan_collections=$main_plan_collections->whereIn("id",$main_plan_ids);

            // メイン案件のIdセットと、メイン案件のIdセットをId名=>そのセットの入れ子配列を返す（同じ案件名があった時のため、same_project_flag,round_nameも条件につけ,表示用にmap_numberもつける）
            return[$main_plan_ids,$plan_collections->groupBy(["project_id","same_project_flag","round_number","map_number"])];
    }

    // その日のサブ案件のデータ一覧を返す
    public static function get_sub_projetcs_sets_in_the_day($main_plan_ids,$sub_plan_collections){

        // メイン案件が該当Idのものを取得(その町目の期限がメインとサブで違う時は考慮されていない)
         $sub_plan_in_the_day_and_projects=$sub_plan_collections->whereIn("main_id",$main_plan_ids);

         // 全サブ案件セットと、メイン案件でグループ分けされたものを返す
         return [$sub_plan_in_the_day_and_projects->groupBy("main_id")];

    }

    // サブ案件も含めたプロジェクトのセット(キーがid&idの数字=>値がプロジェクトのID)
    // mapでは配布外でも対応した報告書(mapになくとも全体表にあることが必要なため、案件に合わせる)
    public static function get_project_names_in_the_report($all_distribution_plans,$main_project_id,$same_project_flag,$round_number,$existed_projects_sets,$main_project_name){
            //その日の報告書に載る併配は、planにおけるproject_id,same_project_flag,round_numberが現在のものに該当するもので、main_idがnullでないものを全て抽出し、そのparoject_idを全てリスト化し、名前を取得する。
            // その営業所(取得済)、その案件(main_id=nullメイン)、sameprojectでroundNumberすれば、案件は1つに決定される。そのidリストを取得。
            $all_id_lists_in_the_main_projects=$all_distribution_plans->where("project_id",$main_project_id)->where("same_project_flag",$same_project_flag)->where("round_number",$round_number)->where("main_id",null)->pluck("id");
            // mapNumberに関係なく、その案件の全部の併配リストを取得。main_idがその案件に入るもの
            $all_sub_lists_in_the_projects=$all_distribution_plans->whereIn("main_id",$all_id_lists_in_the_main_projects)->pluck("project_id")->unique()->mapWithKeys(fn($each_project_id)=>["id".$each_project_id=>$existed_projects_sets[$each_project_id]]);

            return [
                "id".$main_project_id=>$main_project_name,
                ... $all_sub_lists_in_the_projects
            ];
    }

    // 案件セットが得られた後で、それを表示用に並べ替える
    public static function format_project_sets_key_by_project_for_view($main_plans_grouped_by_project_names,$all_distribution_plans,$main_plan_ids_in_the_day,$existed_address_sets,$sub_sets_in_the_day,$existed_projects_sets){
       foreach($main_plans_grouped_by_project_names as $main_project_id=>$main_project_data_sets_with_same_project_flag){

                // メイン案件はidで仕分けされているので、その案件名を返す
                $main_project_name=ProjectHelpers::get_project_name_from_id($main_project_id);

                // same_project_nameはSQLの番号ではなく今回のインデックスで表示
                $same_project_index=1;
                foreach($main_project_data_sets_with_same_project_flag as $same_project_flag=>$main_project_data_sets_with_round_number){
                    // その案件の新旧具合はいくつあるか？(same_project_flagの長さ)
                    $same_project_length=count($main_project_data_sets_with_same_project_flag);
                    // round_numberはSQLの番号ではなく今回のインデックスで表示
                    $round_index=1;
                    foreach($main_project_data_sets_with_round_number as $round_number=>$main_project_data_sets_with_map_number){
                        // そのメイン案件の同時期のもので、今回営業所にきたメイン案件あるか(round_numberの長さ)
                        $round_length=count($main_project_data_sets_with_round_number);
                        // ここのmain_projectは表示用なので変えてもOKかも？round_numberなどと連動して
                        $main_project_key_name_for_view=ChangeProjectNameForView::get_project_name_for_view($main_project_name,$same_project_index,$same_project_length,$round_index,$round_length);
                       // round_indexを1つたす
                        $round_index++;

                        // サブ案件も含めたプロジェクトのセット(キーがid&idの数字=>値がプロジェクトのID)
                        // mapでは配布外でも対応した報告書(mapになくとも全体表にあることが必要なため、案件に合わせる)
                         $return_sets_by_date[$main_project_key_name_for_view]["project_set"]=self::get_project_names_in_the_report($all_distribution_plans,$main_project_id,$same_project_flag,$round_number,$existed_projects_sets,$main_project_name);

                        foreach($main_project_data_sets_with_map_number as $map_number=>$main_project_data_sets_by_map_number){

                            // 必要な世帯数データを抽出する(集合や戸建など)
                            foreach($main_project_data_sets_by_map_number as $main_project_data){
                                $main_plan_id=$main_project_data->id; //そのmainplanのId(複数回使用するので取得)
                                $address_id=$main_project_data->address_id; //住所Id(複数回取得するので先に取得)
                                $return_sets_by_date[$main_project_key_name_for_view]["each_data"][$map_number][]=[
                                    // その日その人に振られたprojectIdは一意に決まり、すでに日と人とではfilterにかけられているので、plan_idからassign_idは取得可能
                                    "assign_id"=>$main_plan_ids_in_the_day->search($main_plan_id),
                                    //  住所のid
                                    "address_id"=>$address_id,
                                    //  住所の名前
                                    "address_name"=>$existed_address_sets[$address_id]["address_name"],
                                    // 世帯数(後日、場合わけ必要)
                                    "household"=>$existed_address_sets[$address_id]["household"],
                                    // 併配がある案件のセット
                                    "sub_sets"=>isset($sub_sets_in_the_day[$main_plan_id]) ? array_column($sub_sets_in_the_day[$main_plan_id]->toArray(),"project_id") : [],
                                ];
                            } //map_numberごと
                        }//round_numberごと
                      $same_project_index++;
                    }//same_projectごと
                }
            }
            return $return_sets_by_date ?? [];
    }


    // 日毎の処理
    public static function get_data_by_date($date,$fetched_assigned_data,$all_distribution_plans,$existed_plan_collections,$sub_plan_collections,$existed_projects_sets,$existed_address_sets){

         // その日に配布もしくはその日が期限内のassignされたデータを取得
            $main_assigned_data_in_date=$fetched_assigned_data->filter(fn($each_data)=>
                $each_data->date==$date || ($each_data->end_data && ($each_data->end_date>=$date || $each_data->date < $date) )
            );

            if($main_assigned_data_in_date->isEmpty()){
                return;
            }

            // その日のメイン案件&same_project_flag&round_number&地図番号のIdセット(assignのid=>planのidの連想配列)、[プロジェクト名をキーにした、その日のplanのセット]の一覧
            [$main_plan_ids_in_the_day,$main_plans_grouped_by_project_names]=self::get_main_projects_data_in_the_day($main_assigned_data_in_date,$existed_plan_collections,$existed_projects_sets);

            // サブ案件のセット。main案件のIdをキーに、idとproject_idを返す
            $sub_sets_in_the_day=$sub_plan_collections->whereIn("main_id",$main_plan_ids_in_the_day)->groupBy("main_id");

            // projectName~MapnNumberのそれぞれを入れ子のキーにしたセットの一覧を返す
            return self::format_project_sets_key_by_project_for_view($main_plans_grouped_by_project_names,$all_distribution_plans,$main_plan_ids_in_the_day,$existed_address_sets,$sub_sets_in_the_day,$existed_projects_sets) ?? [];
    }

}
