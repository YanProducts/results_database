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

}
