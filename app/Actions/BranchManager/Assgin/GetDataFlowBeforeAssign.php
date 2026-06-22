<?php

// 営業所からスタッフ割り当ての流れ
namespace App\Actions\BranchManager\Assgin;

use App\Constants\Date;
use App\Models\BranchManagerList;
use App\Actions\BranchManager\Assgin\DataFetches\GetDataInPlaceAndPeriod;
use App\Actions\BranchManager\Assgin\DataFetches\GetDatePlanIndex;
use App\Actions\BranchManager\Assgin\DataFetches\GetStaffsInPlaceAndPeriod;
use App\Utils\DateHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetDataFlowBeforeAssign{

    // 簡易、詳細版の両方で必要なデータ
    public static function get_common_data(){
        // 今から何日後のデータまで取得するか
        $start_offset=Date::StartOffsetInStaffAssignMent;
        $end_offset=Date::EndOffsetInStaffAssignMent;

        // 現在ログインしている担当のの営業所のid取得
        $place_id=BranchManagerList::where("id",Auth::user()->authable_id)->value("place_id");

        // 日付セット
        $date_sets=DateHelper::get_date_key_value_sets_for_view("",$start_offset,$end_offset);

        // 指定曜日に出勤しているスタッフの取得
        // 日付=>[スタッフリスト(それぞれid=>staff_nameないときはユーザー名)]の順で取得
        //他でも使用する可能性があるので、Support層で定義
        $staffs_in_the_period=GetStaffsInPlaceAndPeriod::get_staffs_in_the_place_and_preriod($place_id,$date_sets);

        return [$start_offset,$end_offset,$place_id,$date_sets,$staffs_in_the_period];
    }

    // 詳細版
    // 日付、営業所に来ている案件、日付とキーのインデックス、現在のスタッフ取得
    public static function get_projects_and_staffs_in_branch(){

        [$start_offset,$end_offset,$place_id,$date_sets,$staffs_in_the_period]=self::get_common_data();

        // 該当期間に計画されている案件を、メイン案件=>[each_sets=>[id(planの),address_name],sub_sets["prject_name"=>,id_sets=>[]]]の形で取得
       $projects_and_towns=GetDataInPlaceAndPeriod::get_projects_and_towns_in_the_place_and_period($place_id,$start_offset,$end_offset);

        // 日付とデータのインデックス
        $date_projects_index=GetDatePlanIndex::get_date_projects_index($date_sets,$projects_and_towns);

        return [$date_sets,$projects_and_towns,$date_projects_index,$staffs_in_the_period];
    }

    // スタッフに割り当てる簡易版のデータを返す
    public static function get_staffs_and_projects_in_branch_for_simple(){

        // 共通データ
        [$start_offset,$end_offset,$place_id,$date_sets,$staffs_in_the_period]=self::get_common_data();

        // メイン案件ごとのplan_idとmapのセット/日付ごとにプロジェクトが格納されたもの
        [$plan_id_and_maps_by_main_projects,$date_projects_index]=GetDataInPlaceAndPeriod::get_project_data_for_simple_pattern($date_sets,$place_id,$start_offset,$end_offset);

        return [$date_sets,$staffs_in_the_period,$plan_id_and_maps_by_main_projects,$date_projects_index];
    }



}
