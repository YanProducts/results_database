<?php

namespace App\Support\Common\ModelHelpers;

use App\Exceptions\BusinessException;
use App\Models\UserAuth;

// UserAuth(authの土台)のヘルパー関数
class UserAuthHelpers{
    //パスワードのリセット(やっているのは認証情報の削除)
    public static function reset_user_authentication($lists_id,$model_name){
        // 指定のデータの取得（メールはlistaにおく）
        $user=UserAuth::where("authable_id",$lists_id)->where("authable_type",$model_name)->first();
        // 存在しない場合はエラー
        if(!$user){
            throw new BusinessException("パスワードをリセットするユーザーのデータが見当たりませんでした");
        }
        $user->delete();
    }
}
