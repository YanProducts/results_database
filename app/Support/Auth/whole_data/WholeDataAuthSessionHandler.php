<?php
namespace App\Support\Auth\whole_data;

use App\Utils\Session;

// whole_dataのログインsession操作
class WholeDataAuthSessionHandler{
    // ログインセッションの生成
    public static function create_login_session($id){
        // sessionがあるかないかで判断。内容は何でも良いが、後々に確認などで使用することも考えidで保存
        Session::create_sessions(["whole_data_auth"=>$id]);

    }
    // ログインセッションの破棄
    public static function delete_login_session(){
        Session::delete_sessions(["whole_data_auth"]);
    }

}
