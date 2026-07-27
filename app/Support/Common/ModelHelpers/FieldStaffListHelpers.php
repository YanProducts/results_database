<?php

// スタッフリストのモデルヘルパー

namespace App\Support\Common\ModelHelpers;
use App\Models\FieldStaffList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FieldStaffListHelpers{

    // 全スタッフを営業所名でgroupByして返す
    public static function get_all_staffs_group_by_place_name($need_inner_group=false){
        // まずは営業所IdでgroupBy
        $data_grouped_by_placed_id=FieldStaffList::all()->groupBy("place_id");
        // そのキーを営業所名にして変更
        return $data_grouped_by_placed_id->mapWithKeys(fn($each_data_by_place,$place_id)=>[PlaceHelpers::get_registered_places()[$place_id]=>($need_inner_group ? $each_data_by_place->mapWithKeys(fn($each_staff_data)=>[$each_staff_data->id=>self::get_real_staff_name($each_staff_data->id)]) : $each_data_by_place)]);
    }


    // ログイン中のユーザーの営業所を返す
    public static function get_place_name_by_user($staff_id){
        // findはモデルを返すので、そのモデルインスタンスとしてplace_idを返せば良い
        return FieldStaffList::findOrFail($staff_id)->place_id;
    }

    // ユーザーのスタッフ名を返す(登録されていなければ「No...」の形式)
    public static function get_real_staff_name($staff_id){
         $instance=FieldStaffList::findOrFail($staff_id);

         return $instance->staff_name ?? $instance->user_name;
    }

    // 特定営業所のスタッフの「スタッフ名→ないときはid」をidをキーにして返す
    public static function get_all_names_of_staffs_in_the_place($place_id){
        return FieldStaffList::select("id",DB::raw("COALESCE(staff_name,user_name) as staff_name"))->where("place_id",$place_id)->get();
    }

}
