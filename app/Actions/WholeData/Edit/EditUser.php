<?php

// ユーザーの情報変更
namespace App\Actions\WholeData\Edit;

use App\Support\Auth\UserRoleResolver;

class EditUser{
    // 対象ユーザーが決定し、そのユーザーの現在の情報を取得
    public static function get_user_information($role,$id){
        // モデルから取得
        $information=UserRoleResolver::get_user_information($role,$id);
        // その情報から必要なものをまとめる
        return[
            "role"=>$role,
            "id"=>$id,
            "userName"=>$user_name=$information->user_name,
            "staffName"=>$role=="field_staff" ? ($information->staff_name ??  $user_name ) : "",
            "placeId"=>in_array($role,["field_staff",""]) ? $information->place_id : "", //idを取得し、別途全体で変更する時のことを考えてplace_nameを取得
        ];

    }
}
