<?php

// Login操作に関する部分
namespace App\Actions\Auth;

use App\Exceptions\BusinessException;
use App\Models\WholeData;
use App\Support\Auth\UserRoleResolver;
use App\Support\Auth\whole_data\WholeDataAuthSessionHandler;
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

        // 全般管理者の場合での検証
        if(str_contains($route,"whole_data")){
            if(self::whole_data_login($user_name,$password)){
                return true;
            }
            return false;
        }

        // roleの場合の検証「（全般管理者でもない場合は既に上記で除外した上で）roleではない場合を除外」)
        if(self::role_login($route,$user_name,$password)){
            return true;
        }


        return false;

    }

    // 全般管理者のログイン操作
    private static function whole_data_login($user_name,$password){
        // モデルの取得
        $whole_data=new WholeData();
        $user_instance=$whole_data->where("user_name",$user_name)->first();

        // ログイン検証
        if($user_instance && Hash::check($password,$user_instance->password)){
            // ログインsession作成(再生はコントローラーで行う)
            WholeDataAuthSessionHandler::create_login_session($user_instance->id);
            return true;
        }

        return false;

    }

    //roleからのログインの場合
    private static function role_login($route,$user_name,$password){
        try{
        // roleモデルの取得(なければ内部でエラーに)
        $model_name=UserRoleResolver::get_model_from_route($route);
        // そのモデルから該当ユーザー名のidを返却
        $user_id=self::get_id_from_auth_data($model_name,$user_name) ?? throw new BusinessException("該当ユーザーが見つかりません");

        return FacadesAuth::attempt([
            "authable_id" => $user_id,
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
