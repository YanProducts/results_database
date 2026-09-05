<?php

namespace App\Actions\BranchManager\Report;

use App\Constants\Date;
use App\Models\BranchManagerList;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\DistributionAssignmentHelpers;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Utils\DateHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// 日付からスタッフを選ぶ場合のリスト
class GetOverviewByDayInDateToStaff{

    // 該当日におけるスタッフの報告データや予定データが存在するか
    public static function get_staff_status_and_city_names_in_each_day(){

        // いつからいつのデータか
        $date_sets=DateHelper::get_date_key_value_sets_for_view("",Date::StartOffsetInConfirmReportPeriodForManager,Date::EndOffsetInConfirmReportPeriodForManager);

        // n+1対策にSQLデータを先に取得
        [$record_sets,$only_assigned_sets,$date_addrss_sets,$city_names_key_by_id]=self::get_sql_data($date_sets);

        // 取得したデータを元に、日付=>[報告済スタッフ、割当のみスタッフ、市の名前リスト]に並び替えたものを返す
        return self::sort_data_by_date($date_sets,$record_sets,$only_assigned_sets,$date_addrss_sets,$city_names_key_by_id);

    }

    public static function get_sql_data($date_sets){

        // 全スタッフ(id=>スタッフ名)
        $all_staff_lists=FieldStaffListHelpers::get_all_names_of_staffs_in_the_place($place_id=BranchManagerListHelpers::get_login_user_place_id());

        // 上記のスタッフ、時期における結果
        $record_sets=DistributionRecordHelpers::data_in_the_range_and_staffs($date_sets,$staff_ids=$all_staff_lists->pluck("id"));

        // 上記のスタッフ、時期にassignされたデータのうち配布完了していないもの(statusで取得)
        $only_assigned_sets=DistributionAssignmentHelpers::get_not_submitted_data_from_plural_staff_ids($staff_ids,$record_sets,$date_sets)->get()->groupBy("date");



        // ここをassignしたplanに限定するかは要考慮！

        // その日のplanから全体でいく市の名前を取得するためにアドレスidを取得
        $date_addrss_sets=DistributionPlanHelpers::get_address_ids_in_place_and_date_sets($place_id,array_keys($date_sets))->get();



        // idをキーにした市の名前のセット
        $city_names_key_by_id=AddressHelpers::get_only_city_name_from_ids($date_addrss_sets->pluck("address_id"));

        return [$record_sets->groupBy("distribution_date"),$only_assigned_sets,$date_addrss_sets,$city_names_key_by_id];
    }

    // 取得したデータを[日付=>配布済スタッフ、割り当てのみスタッフ、市の名前]に並び替える
    public static function sort_data_by_date($date_sets,$record_sets_grouped_by_date,$only_assigned_sets,$date_addrss_sets,$city_names_key_by_id){

        return  collect(array_keys($date_sets))->mapWithKeys(fn($each_date)=>([$each_date=>[
            "recorded"=>($record_sets_grouped_by_date[$each_date] ?? collect())->pluck("staff_id")->unique()->values(),

            "only_assigned"=>($only_assigned_sets[$each_date] ?? collect())->pluck("staff_id")->unique()->values(),

            // 市の名前(該当期間の1日でも可能性があれば取得)
            // ここが取れてない！！
            "city_names"=>($date_addrss_sets->where("start_date","<=",$each_date)->where("end_date",">=",$each_date)->pluck("address_id"))->map(fn($each_address_id_in_the_day)=>$city_names_key_by_id[$each_address_id_in_the_day])->unique()->values(),
        ]]));
    }

}
