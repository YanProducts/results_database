<?php

// 営業所からスタッフ割り当ての流れ
namespace App\Actions\BranchManager\Assgin;

use App\Models\BranchManagerList;
use App\Support\BranchManager\GetDataInPlaceAndPeriod;
use App\Support\BranchManager\GetStaffsInPlaceAndPeriod;
use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use App\Utils\DateHelper;
use Illuminate\Support\Facades\Auth;

class Flow{
    // 現在、営業所に来ている案件と、現在のスタッフ取得
    public static function get_projects_and_staffs_in_branch(){

        // 今から何日後のデータまで取得するか
        $start_offset=0; $end_offset=7;

        // 現在ログインしている担当のの営業所のid取得
        $place_id=BranchManagerList::where("id",Auth::user()->authable_id)->value("place_id");

        // 該当期間に計画されている案件を、main配列は日付=>メイン案件=>[id(投稿用),town=>町目]、sub配列は日付=>メイン案件⇨サブ案件=>[id(投稿用),town=>町目]の形式で取得
       [$main_projects_and_towns,$sub_project_and_towns]=GetDataInPlaceAndPeriod::get_projects_and_towns_in_the_place_and_period($place_id,$start_offset,$end_offset);

        // 指定曜日に出勤しているスタッフの取得
        // 日付=>[スタッフリスト(それぞれid,user_name,staff_nameが入れ子)]の順で取得
        $staffs_in_the_period=GetStaffsInPlaceAndPeriod::get_staffs_in_the_place_and_preriod($place_id,"",$start_offset,$end_offset);

        return [$main_projects_and_towns,$sub_project_and_towns,$staffs_in_the_period];
    }
}
