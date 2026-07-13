<?php

// スタッフリストのモデルヘルパー

namespace App\Support\Common\ModelHelpers;
use App\Models\FieldStaffList;

class FieldStaffListHelpers{
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

}
