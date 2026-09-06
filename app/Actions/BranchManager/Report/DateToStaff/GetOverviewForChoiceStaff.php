<?php

namespace App\Actions\BranchManager\Report\DateToStaff;

use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\DistributionAssignmentHelpers;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Log;

// 日付からスタッフを選ぶ場合の第二段階のスタッフ選びのリスト
class GetOverviewForChoiceStaff{
    // 日付決定後、その日付に来ているスタッフを[id,staff_name,status=報告書提出状況、towns=スタッフが行く町目]で選択
    public static function get_staffs_information_in_the_day($date){

        // その日に稼働するスタッフ、そのスタッフに割り当てられた案件(staff=>planids)、割り当て案件プランからaddressとproject(planids=>adreess_id&project_id)、それぞれ住所と案件のid
        [$all_staff_lists,$assigned_data,$main_assigned_plans_data,$address_sets,$project_sets,$recorded_staffs]=self::get_sql_data($date);

        // その日付に来ているスタッフのデータを[id,staff_name,status=報告書提出状況、towns=スタッフが行く町目いくつか、projects=スタッフに割り当てられたメイン案件]で並び替たものを返す
        return self::get_sorted_data($all_staff_lists,$assigned_data,$main_assigned_plans_data,$address_sets,$project_sets,$recorded_staffs);
    }

    public static function get_sql_data($date){
        // 営業所の全スタッフ(id=>スタッフ名)
        $all_staff_lists=FieldStaffListHelpers::get_all_names_of_staffs_in_the_place(BranchManagerListHelpers::get_login_user_place_id());

        // その日に、上記のスタッフにassignされているplan_idを[staff=>planidの配列]で返す
        $assigned_data=DistributionAssignmentHelpers::get_staff_and_plan_ids_in_the_date($date,$staff_ids=$all_staff_lists->pluck("id"));

        // plan_idからaddress_idとメインのproject_idを取得し、keyをplan_idでまとめる
        $main_assigned_plans_data=DistributionPlanHelpers::get_main_data_for_insert_from_plan_id($assigned_data->flatten()->unique()->values());

        // 住所データからidに入る市の名前の取得し、id=>住所を取得し配列で変換
        $address_sets=AddressHelpers::get_city_and_town_arrays_key_by_id($main_assigned_plans_data->pluck("address_id"));

        // 案件データからidに入る案件の名前を取得し、id=>案件を取得し配列で変換
        $project_sets=ProjectHelpers::get_project_names_array_key_by_id($main_assigned_plans_data->pluck("project_id"));

        // その日に、上記のスタッフにより提出されているスタッフのidセットを返す
        $recorded_staffs=DistributionRecordHelpers::get_recorded_staffs_in_the_date_and_staffs($date,$staff_ids);

        return [$all_staff_lists,$assigned_data,$main_assigned_plans_data,$address_sets,$project_sets,$recorded_staffs];
    }

    // その日付に来ているスタッフのデータを[id,staff_name,status=報告書提出状況、towns=スタッフが行く町目いくつか、projects=スタッフに割り当てられたメイン案件]で並び替え
    public static function get_sorted_data($all_staff_lists,$assigned_data,$main_assigned_plans_data,$address_sets,$project_sets,$recorded_staffs){

    Log::info("assignされたデータ");
    Log::info($assigned_data);

    Log::info("assignされているメイン案件");
    Log::info($main_assigned_plans_data);

        // all_staffsはid=>スタッフの名前
       return
        $all_staff_lists->map(function($staff_data,$staff_id)use($assigned_data,$main_assigned_plans_data,$address_sets,$project_sets,$recorded_staffs){

            // それぞれのスタッフに割り当てられたデータのプランidの取得
            $plan_ids_in_the_date_and_staff=$assigned_data[$staff_id] ?? collect();

            // 上記プランidを元に、住所と案件の配列の取得
            $address_name_lists=[];
            $project_name_lists=[];
            foreach($plan_ids_in_the_date_and_staff as $plan_id){
                // 重複していないものを格納(4併配など、町目も重複する可能性自体は存在)
                !in_array($address_name=$address_sets[$main_assigned_plans_data[$plan_id]->address_id],$address_name_lists) && $address_name_lists[]=$address_name;

                // 重複していないものを格納
                !in_array($project_name=$project_sets[$main_assigned_plans_data[$plan_id]->project_id],$project_name_lists) && $project_name_lists[]=$project_name;
            }

        return
            [
            "id"=>$staff_id,
            "staff_name"=>$staff_data["staff_name"],
            "projects"=>implode("、",$project_name_lists), //割り当てられた案件
            "towns"=>implode("、",$address_name_lists), //割り当てられた町名
            "status"=>$recorded_staffs->contains($project_sets) ? "済" : "未", //報告書提出状況
            ];
        });

    }


}
