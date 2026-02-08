<?php

// 既に統括者がリストに追加しているユーザーかのチェック

namespace App\Support\Auth;

class CheckUserInLists{
    // そのユーザーを統括者がリストに加えているかのチェック(YesNoのみ)
    public static function check($model,$user_name){
        return($model::where("user_name",$user_name)->exists());
    }
    // そのユーザーのインスタンスを返す、存在しなければエラーが返る
    public static function get_user_auth_id($model,$user_name){
        return $model::where("user_name",$user_name)->firstOrFail();
    }

}
