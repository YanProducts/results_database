<?php

// 営業所からスタッフ割り当ての流れ
namespace App\Actions\BranchManager\Assgin;

use App\Constants\Date;
use App\Models\BranchManagerList;
use App\Support\BranchManager\GetDataInPlaceAndPeriod;
use App\Support\BranchManager\GetStaffsInPlaceAndPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetDataFlowBeforeAssign{
    // 現在、営業所に来ている案件と、現在のスタッフ取得
    public static function get_projects_and_staffs_in_branch($start_offset,$end_offset){

        // 今から何日後のデータまで取得するか
        $start_offset=Date::StartOffsetInStaffAssignMent;
        $end_offset=Date::EndOffsetInStaffAssignMent;

        // 現在ログインしている担当のの営業所のid取得
        $place_id=BranchManagerList::where("id",Auth::user()->authable_id)->value("place_id");

        // 該当期間に計画されている案件を、日付=>メイン案件=>[each_sets=>[id(planの),address_name],sub_sets["prject_name"=>,id_sets=>[]]]の形で取得
        //他でも使用する可能性があるので、Support層で定義
       $projects_and_towns=GetDataInPlaceAndPeriod::get_projects_and_towns_in_the_place_and_period($place_id,$start_offset,$end_offset);

        // 指定曜日に出勤しているスタッフの取得
        // 日付=>[スタッフリスト(それぞれid=>staff_nameないときはユーザー名)]の順で取得
        //他でも使用する可能性があるので、Support層で定義
        $staffs_in_the_period=GetStaffsInPlaceAndPeriod::get_staffs_in_the_place_and_preriod($place_id,"",$start_offset,$end_offset);

        return [$projects_and_towns,$staffs_in_the_period];
    }


}
