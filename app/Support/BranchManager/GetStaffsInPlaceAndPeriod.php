<?php

// その営業所で、その期間に出勤するスタッフを返す
// 本来はシフトサイト連動。ひとまずは実験的に営業所全員のデータを返す

namespace App\Support\BranchManager;

use App\Models\FieldStaffList;
use Illuminate\Support\Facades\DB;

class GetStaffsInPlaceAndPeriod{
    // その期間に該当営業所で出勤するスタッフを取得

    public static function get_staffs_in_the_place_and_preriod($place_id,$base_date,$start_offset,$end_offset){

        // 本来はシフトサイト連動だが、簡易的に現在のデータ全てから取得
        // return FieldStaffList::select("id","user_name","staff_name")->where("place_id",$place_id)->get();
        return DB::table("field_staff_lists")->selectRaw("id, coalesce(staff_name,user_name) as staff_name")->where("place_id",$place_id)->pluck("staff_name","id");
    }
}
