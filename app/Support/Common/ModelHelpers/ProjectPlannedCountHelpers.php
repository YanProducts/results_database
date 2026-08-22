<?php

namespace App\Support\Common\ModelHelpers;

use App\Constants\Date;
use App\Models\ProjectPlannedCount;
use Carbon\Carbon;

class ProjectPlannedCountHelpers{

    // 開始日が指定月以内(指定がなければDateに指定された期間)の設定部数などの予定
    public static function get_plans_by_start_day($start_month=null){
        //いつからのデータをとってくるか(デフォルトは1ヶ月前)
        $start_date=Carbon::now()->subMonths($start_month ?? Date::ProjectOverviewStartDateOffsetMonth);
        // この時点ではgropuByしない。formatは別途行う
        return  ProjectPlannedCount::select("id","start_date","place_id","project_id","round_number","end_date","main_id","counts")->where("start_date",">",$start_date)->get();
    }
}
