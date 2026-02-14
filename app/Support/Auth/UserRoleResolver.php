<?php

namespace App\Support\Auth;
use App\Enums\UserRole;
use App\Exceptions\BusinessException;

//UserRoleのEnumから実際に使用する部分を取得する
class UserRoleResolver{

    // どのauthページかを返す
    public static function get_auth_page_type($request){

        // ルートの名前
        $route_name=$request->route()->getName();

        //全般データなら、whole_dataを返す
        if(str_contains($route_name,"whole_data")){
            return
            [
                "prefix"=>"whole_data",
                "what"=>"全体統括"
            ];
        }

        // それ以外ならそのぺーじを返す
        foreach(UserRole::cases() as $role){
            if(str_contains($route_name,$role->value)){
            $value=$role->value;
            $jpn_word=UserRole::get_jpn_description($value);
            // 日本語=>英語の配列で返す
            return [
                "prefix"=>$value,
                "what"=>$jpn_word
            ];
            }
        }
            // 上記以外の場合、ページが見つからない例外を投げる
            // 定義のところでも拾ってくれる
            abort(404);
    }
     // 全Enumを英語=>日本語の配列で返す
    public static function get_all_values(){
    return array_reduce(UserRole::cases(),function($carry,$role){
            $eng=$role->value;
            $carry[$eng]=UserRole::get_jpn_description($eng);
            return $carry;
        },[]);
    }
    
    // ルート名からモデルの名前空間を返す
    public static function get_model_from_route($route){
        // 全てのEnumのインスタンスを取得し、その中からroute名を含むものを抽出
        foreach(UserRole::cases() as $enum_instance){
            // ルート名と合うとき
            if(str_contains($route,$enum_instance->value)){
                // モデル名を返す
                return $enum_instance->get_model_name();
            }
        }
        throw new BusinessException("ルート名が違います");
    }
}
