<?php

// その営業所で、その期間に出勤するスタッフを返す
// 本来はシフトサイト連動。ひとまずは実験的に営業所全員のデータを返す

namespace App\Actions\BranchManager\Assgin\DataFetches;

use App\Models\FieldStaffList;
use App\Utils\DateHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetStaffsInPlaceAndPeriod{
    // その期間に該当営業所で出勤するスタッフを取得

    public static function get_staffs_in_the_place_and_preriod($place_id,$date_sets){

        // その営業所の全スタッフの取得
        $staffs=DB::table("field_staff_lists")->selectRaw("id, coalesce(staff_name,user_name) as staff_name")->where("place_id",$place_id)->pluck("staff_name","id");

        // 本来はシフトサイト連動だが、簡易的に現在のデータ全てから取得
        // date=>出席スタッフで返す(現段階では全スタッフ)
        return collect($date_sets)->mapWithKeys(fn($date_in_jpn,$date_in_Ymd)=>[$date_in_Ymd=>$staffs]);

    }
}
