<?php
// Auth認証へルートが正しいかのの確認(バリデーションのauthorize等から呼び出すヘルパー関数)
namespace App\Support\Auth;

use App\Enums\UserRole;

class AuthTypeChack{
    public static function is_valid_auth_rule($value){
        // 全般データ系ならOK
        if (str_contains($value,'whole_data')) {
            return true;
        }

        //Enumに合うものはOK
        foreach(UserRole::cases() as $role_instance){
            if(str_contains($value,$role_instance->value)){
                return true;
            }
        }
        return false;
    }
}
