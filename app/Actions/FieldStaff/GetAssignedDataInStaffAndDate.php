<?php
// その日のそのスタッフにおける、割り当てられたデータ
namespace App\Actions\FieldStaff;
use App\Models\DistributionAssignment;
use App\Models\DistributionPlan;
use App\Support\CommonModelHelpers\AddressHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Log;

class GetAssignedDataInStaffAndDate{

    // データの取得
    public static function get_assigned_data($staff_id,$date_sets){

            // sqlに入っている、そのスタッフの、期間内を含む案件を取得(n+1防止に一括取得)。それをdateでまとめる
            $data_in_staff_and_date=DistributionAssignment::where("staff_id",$staff_id)
            // 基本的には単日のdateが配列内部にあるとき
            ->whereIn("date",array_keys($date_sets))
            // 複数日可能な案件(end_dateがnullではない)かつdate_setsの最小値より後で、かつdateがdate_setsも最大値より小さいとき
            ->orWhere([
                ["end_date","<>",null],
                ["end_date",">=",min($date_sets)],
                ["date","<=",max($date_sets)]
            ])->get();

            // 構造をフロント用に変更されたデータを返す
            return self::form_data_for_form_and_view($data_in_staff_and_date,$date_sets);

    }

    // データの変更
    public static function form_data_for_form_and_view($fetched_assigned_data,$date_sets){

            // プランのId
            $plan_ids=$fetched_assigned_data->map(fn($each_data)=>$each_data->plan_id);

            // N+1防止のため、SQLデータを先に取得
            [$existed_plan_collections,$existed_projects_sets,$existed_address_sets,$sub_plan_collections]=self::get_data_in_sql($plan_ids);

        // そのスタッフの報告書用のデータ(dateをキーに:メイン案件名がサブキー:[その下位はオブジェクトの配列。addressId,addressName,planId,subSets{"projectName","planId"}]//併配も含めた案件セット})

        // 日付をキーにして保存
        foreach(array_keys($date_sets) as $date){
            // phpはスコープ内宣言でOL
            $return_sets[$date]=self::get_data_by_date($date,$fetched_assigned_data,$existed_plan_collections,$sub_plan_collections,$existed_projects_sets,$existed_address_sets);
        }

        return $return_sets;
    }

    // N+1防止のために一括取得
    public static function get_data_in_sql($plan_ids){

            // assignされた時点で、すでにメイン案件に絞られたものが送られている
            $existed_plan_collections=DistributionPlan::select("id","project_id","round_number","address_id")->whereIn("id",$plan_ids)->get();

            // サブ案件のplanのコレクション
            $sub_plan_collections=DistributionPlan::select("id","project_id","round_number","address_id","main_id")->whereIn("main_id",$plan_ids)->get();

            // 住所のキー=>市町目のセットで返す
            $existed_address_sets=AddressHelpers::get_city_and_town_arrays_key_by_id(array_unique([...$existed_plan_collections->pluck("address_id"),...$sub_plan_collections->pluck("address_id")]));

            // 案件のキー=>(round_numberこみの)案件の名前のセットで返す
            $existed_projects_sets=ProjectHelpers::get_project_names_with_round_number_array_key_by_id(array_unique([...$existed_plan_collections->pluck("project_id"),...$sub_plan_collections->pluck("project_id")]));

            return[$existed_plan_collections,$existed_projects_sets,$existed_address_sets,$sub_plan_collections];
    }

    // メイン案件のデータを返す(main_plan_collectionsはexisted_collectionsと同じ。assignの段階でメインに絞っているため)
    public static function get_main_projects_data_in_the_day($main_assigned_data_in_date,$main_plan_collections,$existed_projects_sets){
            // その日の「assignのid=>planのid」と対応した連想配列を取得(値だけみたらmain_plan_ids)。条件としてmain案件のもののみを取得する
            $main_plan_ids=collect($main_assigned_data_in_date->mapWithKeys(fn($each_data)=>[$each_data->id=>$each_data->plan_id]))->filter(fn($each_plan_id,$each_assign_id)=>$main_plan_collections->where("id",$each_plan_id)->isNotEmpty());

            // その日のplan
            $plan_collections=$main_plan_collections->whereIn("id",$main_plan_ids);

            // メイン案件のIdセットと、メイン案件のIdセットをプロジェクト名=>そのplanIdのセットの一覧を返す（同じ案件名があった時のため、round_nameも条件につける）
            return[$main_plan_ids,$plan_collections->groupBy(fn($row)=>$existed_projects_sets[$row->project_id])];
    }

    // その日のサブ案件のデータ一覧を返す
    public static function get_sub_projetcs_sets_in_the_day($main_plan_ids,$sub_plan_collections,$existed_projects_sets){

        // メイン案件が該当Idのものを取得(その町目の期限がメインとサブで違う時は考慮されていない)
         $sub_plan_in_the_day_and_projects=$sub_plan_collections->whereIn("main_id",$main_plan_ids);

         $all_sub_projects_sets=($sub_plan_in_the_day_and_projects->map(fn($each_plan)=>$each_plan->project_id)->unique())->mapWithKeys(fn($each_project_id)=>[$each_project_id=>$existed_projects_sets[$each_project_id]]);

         // 全サブ案件セットと、メイン案件でグループ分けされたものを返す
         return [$all_sub_projects_sets,$sub_plan_in_the_day_and_projects->groupBy("main_id")];

    }

    // 日毎の処理
    public static function get_data_by_date($date,$fetched_assigned_data,$existed_plan_collections,$sub_plan_collections,$existed_projects_sets,$existed_address_sets){
    // その日に配布もしくはその日が期限内のassignされたデータを取得
            $main_assigned_data_in_date=$fetched_assigned_data->filter(fn($each_data)=>
                $each_data->date==$date || ($each_data->end_data && ($each_data->end_date>=$date || $each_data->date < $date) )
            );

            if($main_assigned_data_in_date->isEmpty()){
                return;
            }

            // その日のメイン案件のIdセット(assignのid=>planのidの連想配列)、[プロジェクト名をキーにした、その日のplanのセット]の一覧
            [$main_plan_ids_in_the_day,$main_plans_grouped_by_project_names]=self::get_main_projects_data_in_the_day($main_assigned_data_in_date,$existed_plan_collections,$existed_projects_sets);

            // サブ案件のセットmain案件のIdをキーに、idとproject_idを返す
            [$all_sub_projects_sets,$sub_sets_in_the_day]=self::get_sub_projetcs_sets_in_the_day($main_plan_ids_in_the_day,$sub_plan_collections,$existed_projects_sets);

            // projectNameをキーにしたセットの一覧を返す
            foreach($main_plans_grouped_by_project_names as $main_project=>$main_project_data_sets){

                // サブ案件も含めたプロジェクトのセット
                $return_sets_by_date[$main_project]["project_set"]=[
                     $existed_projects_sets->search($main_project)=>$main_project,
                    ...$all_sub_projects_sets
                ];

                foreach($main_project_data_sets as $main_project_data){
                    $main_plan_id=$main_project_data->id; //そのmainplanのId(複数回使用するので取得)
                    $address_id=$main_project_data->address_id; //住所Id(複数回取得するので先に取得)
                    $return_sets_by_date[$main_project]["each_data"][]=[
                        // その日その人に振られたprojectIdは一意に決まり、すでに日と人とではfilterにかけられているので、plan_idからassign_idは取得可能
                         "assign_id"=>$main_plan_ids_in_the_day->search($main_plan_id),
                        //  住所のid
                         "address_id"=>$address_id,
                        //  住所の名前
                         "address_name"=>$existed_address_sets[$address_id],
                         // 併配対策
                         "sub_sets"=>$sub_sets_in_the_day[$main_plan_id],
                     ];
                 }
            }
            return $return_sets_by_date;
    }

}
