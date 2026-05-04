<?php

namespace App\Http\Controllers\FieldStaffs;

use App\Actions\FieldStaff\GetAssignedDataInStaffAndDate;
use App\Constants\Date;
use App\Http\Controllers\Controller;
use App\Models\FieldStaffList;
use App\Utils\DateHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class WriteReportController extends Controller
{
    //スタッフが実査に報告書入力を行うコントローラー
    // 認証設定はミドルウェアで弾いている(認証項目が違う場合も含む)
    public function write_report(){

        // スタッフのid
        $staff_id=Auth::user()->authable_id;

        // Constantsに書いている報告書記入が許される期間を取得
        $date_sets=DateHelper::get_date_key_value_sets_for_view(Carbon::now()->format("Y-m-d"),Date::StartOffsetInReporPeriod,Date::EndOffsetInReportPeriod);

        // そのスタッフの報告書用のデータ(dateをキーに:メイン案件名がサブキー:[その下位はオブジェクトの配列。addressId,addressName,planId,subSets{"projectName","planId"}]//併配も含めた案件セット})
        $data_in_staff_and_date=GetAssignedDataInStaffAndDate::get_assigned_data($staff_id,$date_sets);

      return Inertia::render("FieldStaff/WriteReport",[
        "prefix"=>"field_staff",
        "what"=>"現場担当",
        "type"=>"報告書入力",
        // スタッフのユーザー名
        "staff"=>FieldStaffList::where("id",$staff_id)->value("user_name"),
        "dateSets"=>$date_sets,
        // そのスタッフに割り当てられたデータ(期限内)
        "assignDataToStaff"=>$data_in_staff_and_date
      ]);

    }
}
