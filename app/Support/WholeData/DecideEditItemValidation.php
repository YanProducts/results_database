<?php

// 編集項目を決定した時のバリデーション(getできたもののため、自ら定義してメッセージを返却)
namespace App\Support\WholeData;

use App\Models\BranchManagerList;
use App\Models\ClericalList;
use App\Models\FieldStaffList;
use App\Models\ProjectOperatorList;
use App\Support\Auth\UserRoleResolver;

class DecideEditItemValidation{

    // 変化させたいユーザーが決まった時のバリデーション
    public static function decide_change_user_validation($role,$id){
        $messages=[];

        //職種が想定されたものか
        if(!UserRoleResolver::role_name_check($role)){
            $messages["role"]="職種が存在しません";
            }

        if(UserRoleResolver::check_id_exisits($role,$id)){
            $messages["id"]="該当ユーザーが見当たりませんでした";
        }
        return $messages;
    }

}

