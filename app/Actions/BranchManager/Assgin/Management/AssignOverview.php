<?php

// 割り当ての確認/編集における流れ
namespace App\Actions\BranchManager\Assgin\Management;

use App\Constants\Date;
use App\Exceptions\BusinessException;
use App\Models\DistributionProjectAssignment;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\DistributionAssignmentHelpers;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\DistributionProjectAssignmentHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use App\Support\Common\ModelHelpers\ProjectPlannedCountHelpers;
use App\Utils\DateHelper;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;

class AssignOverview{
    // 割り当て可能な日時における一覧を、「日付=>この営業所に振られたMainproject(plan)=>そのprojectを配布する人」という配列で取得
    public static function get_overview_data(){
        // 期間の取得
        $date_sets=DateHelper::get_date_key_value_sets_for_view(CarbonImmutable::now()->format("Y-m-d"),Date::StartOffsetInStaffAssignMent,Date::EndOffsetInStaffAssignMent);

        // 営業所idの取得
        $place_id=BranchManagerListHelpers::get_login_user_place_id();

        // 上記の期間&営業所における、planとassignのデータ
        [$staff_lists,$plan_sets_in_the_place,$project_sets,$city_name_sets,$assign_sets,$assigns_by_project_only,$project_plan_index]=self::get_sql_data(array_keys($date_sets),$place_id);

        // 「日付=>この営業所に振られたMainproject(plan)=>そのprojectを配布する人」という配列で並び替えたものを返す
        return self::sort_data($date_sets,$staff_lists,$plan_sets_in_the_place,$project_sets,$city_name_sets,$assign_sets,$assigns_by_project_only,$project_plan_index);
    }

    public static function get_sql_data($date_key_sets,$place_id){

        // この営業所の全員のid=>staff_nameの取得
        $staff_lists=FieldStaffListHelpers::get_all_names_of_staffs_in_the_place($place_id)->pluck("staff_name","id");

        // その期間にこの営業所に来ている案件のセットを返す(表示と選択用のみに使用するため、main_idがnullのものに限定)
        $plan_sets_in_the_place=DistributionPlanHelpers::get_plan_in_the_place_and_period($place_id,["start"=>$start_date=min($date_key_sets),"end"=>$end_date=max($date_key_sets)])->where("main_id",null);

        // 選択&表示用：上記で取得したplanに含まれるprojectIdに対応するprojectの名前を検索(プロジェクトid=>名前)
        $project_sets=ProjectHelpers::get_project_names_with_another_project_flag_array_key_by_id($plan_sets_in_the_place->pluck("project_id")->unique());

        // 表示用のみ：上記で取得したplanに含まれるaddressIdに対応する市の名前を取得(addressid=>市の名前を配列で)
        $city_name_sets=AddressHelpers::get_only_city_name_from_ids($plan_sets_in_the_place->pluck("address_id")->unique());

        // 上記のplanのidに対応したassignを取得
        $assign_sets=DistributionAssignmentHelpers::get_assigns_by_plan_ids($plan_sets_in_the_place->pluck("id"));

        // project版の案件(これは日付->メイン案件のID(projectPlanIdからproject_idとround_numberを取得)=>スタッフの名前で取得可能)
        $assigns_by_project_only=DistributionProjectAssignmentHelpers::get_assigns_between_the_date($staff_lists->keys(),$start_date,$end_date);

        // project版の案件を取得する際に必要なproject_id,round_numberとprojectplanidの連動(mainのみ、この営業所のみ、開始終了のdateはoffset)
        $project_plan_index=ProjectPlannedCountHelpers::get_id_and_project_names_for_view_in_date_array(Date::StartOffsetInStaffAssignMent,Date::EndOffsetInStaffAssignMent,$place_id,true);

        return [$staff_lists,$plan_sets_in_the_place,$project_sets,$city_name_sets,$assign_sets,$assigns_by_project_only,$project_plan_index];
    }

    // 「日付=>この営業所に振られたMainproject(plan)と該当する市のセット=>そのprojectを配布する人」という配列で並び替えたものを返す
    public static function sort_data($date_sets,$staff_lists,$plan_sets_in_the_place,$project_sets,$city_name_sets,$assign_sets,$assigns_by_project_only,$project_plan_index){

        $return_sets=[];
        // 日付ごとに見ていく
        foreach($date_sets as $date=>$date_in_jpn){

            // この日(範囲あり)に来ているidsとメイン案件名とaddress_idsを取得(同案件で別の現場の場合はround_numberで所得して①②で名前分け)
            $main_project_sets_in_the_day=self::get_main_projects_data_in_the_day($plan_sets_in_the_place,$project_sets,$date);

            // 編集や削除可能かのフラグ。今後次第でつける

            $project_data_sets=[];
            // その日にその案件が振られているスタッフ名を取得
            foreach($main_project_sets_in_the_day as $project_name_with_round_number=>$data_by_main){

                // groupByを行ったprojectのidとround_numberの取得
                [$project_id,$round_number]=explode("|",$project_name_with_round_number);

                // 案件名(表示用)=>[市の名前、スタッフの名前]で取得
                $project_data_sets[$project_name_with_round_number]=[
                    "cityNames"=>self::get_city_names_by_address_ids($city_name_sets,$data_by_main->pluck("address_id")), //市の名前リストから市の名前の一覧の文字列を取得
                    "staffs"=>self::get_staff_names_by_plan_ids($date,$staff_lists,$assign_sets,$assigns_by_project_only,$data_by_main->pluck("id"),$project_plan_index,$project_id,$round_number) //その日にその案件を振られているスタッフ //project側からも取得
                ];
            }

            // 日付ごとに格納。いずれも表示用のみ
            $return_sets[$date]=[
                "dateInJpn"=>$date_in_jpn,//日付
                "project_sets"=>$project_data_sets//メイン案件名をキーに市の名前とスタッフがリスト形式で渡っている
            ];
        }
        return $return_sets;
    }

    // その日(範囲)におけるメイン案件名と市の名前を取得し、表示用の案件名とround_numberでまとめて返す
    // できれば共通関数に
    public static function get_main_projects_data_in_the_day($plan_sets_in_the_place,$project_sets,$date){

        return
        $plan_sets_in_the_place->select("id","address_id","project_id","round_number","start_date","end_date")->where("start_date","<=",$date)->where("end_date",">=",$date)->groupBy(
            function($query)use($project_sets){
                // 表示は後で直す
                //データの照合のために、そのままの値を使用
                    return $query["project_id"]."|".$query["round_number"];
            });
    }

    // その日、そのaddress_ids(複数)に割り当てあられている、市の名前の取得
    // できれば共通関数に
    public static function get_city_names_by_address_ids($city_name_sets,$address_ids){
        // 市の名前リストから、address_idsをキーに含むコレクションを返し、文字列にする
        return $city_name_sets->only($address_ids->toArray())->unique()->values()->implode("・");
    }

    // その日、そのplan_ids(複数)に割り当てられている、スタッフの名前の取得(この時点で案件とround_numberは1つに決定している)
    // できれば共通関数に
    public static function get_staff_names_by_plan_ids($date,$staff_lists,$assign_sets,$assigns_by_project_only,$plan_ids,$project_plan_index,$project_id,$round_number){


        //mapとtownからassignされているデータ
        //plan_idが所定のデータを含むものから、スタッフのidを返す
        // 日付でfilter、onlyはeloquent collectionだと別の仕様になるので、通常のcollectionのためにtoBase()が必要。1つのplan_idを複数人のスタッフが言っている場合は複数人のスタッフが配列で格納されているので、flattenで並列にする
        // uniqueとvaluesは後ほどまとめて行う
        $staff_ids_by_the_plans=($assign_sets->toBase()->only($plan_ids)->flatten(1)->filter(fn($assign) => $assign->date == $date)->pluck("staff_id"));

        // 案件からのデータ
        // その日からなければ次へ
        // project_plan_indexにおいて、営業所、project_id、round_numberが決定した案件は一位に決まる。営業所では既にsort済み（なので、project_plan_idはwhereからのvalueで求める。valueはない場合はnullが返る）。

        $staff_ids_by_project_only=!$assigns_by_project_only->has($date) ? collect() :             $staff_ids_by_project_only=$assigns_by_project_only[$date]->where("project_plan_id",($project_plan_index->where("project_id",$project_id)->where("round_number",$round_number)->value("id")))->pluck("staff_id");

        // それぞれでとってきたスタッフIdを連結し、重複除去
        $staff_ids_in_the_projects=$staff_ids_by_the_plans->concat($staff_ids_by_project_only)->unique()->values();

        return $staff_ids_in_the_projects->map(fn($each_staff_id)=>$staff_lists[$each_staff_id])->implode("・");
    }

}
