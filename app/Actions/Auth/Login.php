<?php

// Login操作に関する部分
namespace App\Actions\Auth;

use App\Exceptions\BusinessException;
use App\Models\WholeData;
use App\Support\Auth\UserRoleResolver;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

class Login{

    // ログインを試みて成否を返す
    public static function attempt_login($request){
        // ルート名の取得
        $route=$request->route()->getName();
        // ユーザー名の取得
        $user_name=$request->userName;
        // パスワードの取得
        $password=$request->passWord;

        // 全般管理者の場合とそれ以外の場合に分けて検証
        if((str_contains($route,"whole_data") &&self::whole_data_login($user_name,$password)) || self::role_login($route,$user_name,$password)){
            return true;
        }

        return false;

    }

    // 全般管理者のログイン操作
    private static function whole_data_login($user_name,$password){
        // モデルの取得
        $whole_data=new WholeData();
        $password_in_sql=$whole_data->where("user_name",$user_name)->value("password") ?? throw new BusinessException("該当ユーザーが見つかりおません");
        // 検証
        return Hash::check($password,$password_in_sql);

    }

    //roleからのログインの場合
    private static function role_login($route,$user_name,$password){
        try{
        // roleモデルの取得(なければ内部でエラーに)
        $model_name=UserRoleResolver::get_model_from_route($route);
        // そのモデルから該当ユーザー名のidを返却
        $user_id=self::get_id_from_auth_data($model_name,$user_name) ?? throw new BusinessException("該当ユーザーが見つかりません");

        return FacadesAuth::attempt([
            "authorized_id" => $user_id,
            "password"  => $password
        ]);

        //ルート名の違いのときのみここへ、それ以外は全体のExceptonで捕捉
        }catch(\UnhandledMatchError $e){
            throw new BusinessException("ルートが想定と違います");
        }
    }


    // roleのid取得
    public static function get_id_from_auth_data($model_name,$user_name){
        // そのモデルのインスタンスの取得
        $model_instance=new $model_name();
        // ユーザー名からidを取得
        return $model_instance->where("user_name",$user_name)->value("id");
    }


}
