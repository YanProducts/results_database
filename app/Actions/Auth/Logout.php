<?php

// ログアウトの操作
namespace App\Actions\Auth;

use App\Exceptions\BusinessException;
use App\Support\Auth\whole_data\WholeDataAuthSessionHandler;
use Illuminate\Support\Facades\Auth;

class Logout{
    // ログアウト(whole_dataが入っているかどうかで、どのsessionを削除するかを変更する)
    public static function logout($prefix){

        // 全般統括の場合
        if($prefix=="whole_data"){
            //ログインセッションの破棄
            WholeDataAuthSessionHandler::delete_login_session();
            return;
        }

        // 全般統括でない場合はAuthの破棄
        Auth::logout();
    }

}
