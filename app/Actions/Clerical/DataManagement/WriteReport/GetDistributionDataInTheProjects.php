<?php

namespace App\Actions\Clerical\DataManagement\WriteReport;

use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Support\Common\ModelHelpers\PlaceHelpers;
use Illuminate\Support\Facades\Log;

// そのプロジェクトに対する配布データの取得
// 町目分割で営業所を分ける場合はplan_idがそもそも別
// planIn=>[町目、営業所、(配布後の)スタッフ名、(配布後の)部数、(配布後の)日付]で取得
// スタッフと配布部数と日付は複数人or複数日で配布している場合もあるので入れ子にする
class GetDistributionDataInTheProjects{
    // 報告書データを取得する流れ
    public static function get_data_for_editting_report_by_the_project($project_id){

        // sqlから情報をとってくる
        [$planned_data_sets,$address_household_sets,$place_sets,$recorded_sets,$staff_name_sets]=self::get_data_in_sql($project_id);

        // 上記に書いた形に並び替える
        return self::get_sorted_date($planned_data_sets,$address_household_sets,$place_sets,$recorded_sets,$staff_name_sets);
    }

    // sqlから取得
    public static function get_data_in_sql($project_id){
        // そのプロジェクトIdに該当するplanされた町目(projectId=>[planId,営業所,町目]の配列)
        // projectの名前が一緒でanother_projectの場合はそもそもprojectIdが違うので考慮しなくても良い
        $planned_data_sets=DistributionPlanHelpers::get_planned_data_by_the_project_id($project_id);

        // 住所の名前一覧を返す(id=>住所の連想配列で返る)
        $address_household_sets=AddressHelpers::get_address_name_and_household_set_arrays_key_by_id($planned_data_sets->pluck("address_id")->unique());

        // 営業所の名前一覧を返す(id=>営業所の名前)
        $place_sets=PlaceHelpers::get_place_name_from_ids($planned_data_sets->pluck("place_id")->unique());
        // 該当planに対応する配布結果(複数人が行っている場合があるので、入れ子の配列で取得)
        $recorded_sets=DistributionRecordHelpers::get_record_sets_in_the_plan_ids_group_by_plan_ids($planned_data_sets->pluck("plan_id"));
        $staff_name_sets=FieldStaffListHelpers::get_staff_names_from_id_array(($recorded_sets->collapse())->pluck("staff_id"));

        return[$planned_data_sets,$address_household_sets,$place_sets,$recorded_sets,$staff_name_sets];
    }

    // データの並び替え
    public static function get_sorted_date($planned_data_sets,$address_household_sets,$place_sets,$recorded_sets,$staff_name_sets){
        // plan_id=>町名、営業所名、[スタッフ、日付、配布数]
        return
        $planned_data_sets->mapWithKeys(function($each_plan_data)use($place_sets,$address_household_sets,$recorded_sets,$staff_name_sets){
            $plan_id=$each_plan_data->plan_id;
            $records_by_plan_id=$recorded_sets[$plan_id];
            $address_set_key_by_id=$address_household_sets[$each_plan_data->address_id];

            // records_by_plan_idの中に、配列でスタッフ、日付、部数などが入ってる
            return[$plan_id=>[
                "town_name"=>$address_set_key_by_id["city"].$address_set_key_by_id["town"],
                "house_hold"=>$address_set_key_by_id["household"], //世帯数
                "place_name"=>$place_sets[$each_plan_data->place_id], //営業所名
                "total_counts"=>$records_by_plan_id->pluck("distribution_count")->sum(), //現在配布部数
                "detail_data"=>$records_by_plan_id->map(fn($each_record)=>
                "スタッフ:".$staff_name_sets[$each_record["staff_id"]]."、日付:".$each_record["distribution_date"]."、部数:".$each_record["distribution_count"]
                )->implode("\n") //スタッフ・日付・部数のセット(複数人の場合は複数行で文字列として繋げる)
            ]];
        });
    }
}
