<?php

// 営業所長のリストのモデルのヘルパー関数
namespace App\Support\Common\ModelHelpers;

use App\Exceptions\BusinessException;
use App\Models\BranchManagerList;
use Illuminate\Support\Facades\Auth;

class BranchManagerListHelpers{
    // 現在ログイン中のユーザーの所属する営業所のidを返す
    public static function get_login_user_place_id(){
        if(Auth::user()->authable_type!=="App\Models\BranchManagerList"){
            throw new BusinessException("ルートが不正です");
        }
        return BranchManagerList::findOrFail(Auth::user()->authable_id)->place_id;
    }

}

