<?php

// 現在登録中の情報を取得して表示する

namespace App\Actions\WholeData;

use App\Enums\UserRole;
use App\Models\Place;
use App\Support\Auth\UserRoleResolver;
use App\Support\WholeData\OverViewHelpers;
use Illuminate\Support\Facades\Log;

class Overview{
    // 現在のsqlのデータをtypeに応じて取得
    public static function get_data_in_sql($type){
        // 全ユーザーデータを連想配列をネストした配列で取得
        if(in_array($type,["all","user"])){
            $user_data_sets=self::get_all_users();
            }
        // 全営業所データを連想配列をネストした配列で取得
        if(in_array($type,["all","place"])){
            $place_data_sets=self::get_all_places();
        }
        return [$user_data_sets ?? [], $place_data_sets ?? []];
    }
    // 事前登録されたユーザーのデータを取得
    private static function get_all_users(){
        return array_reduce(UserRole::cases(),function($carry,$role_enum_instance){
            // roleの名前の取得(複数回使う)
            $role_name=$role_enum_instance->value;
            // そのインスタンスの値から、モデル名取得
            $model_name=UserRoleResolver::get_model_from_route($role_name);
            // モデルのインスタンス取得
            $model_instance=new $model_name();

            // そのroleのユーザーで必要とするカラムをコレクションで取得
            $all_user_data_in_role=$model_instance::select(OverViewHelpers::get_querys($role_name,$model_instance))->get();

            // 外側のコレクションのキーではなく、内側のモデルインスタンスを配列化したもののキーを変更していくのでmapで良い。
            $all_user_data_with_role=$all_user_data_in_role->map(fn($each_user)=>[...$each_user->toArray(),
            // placeIdからplace_nameを取得する
            "place_name"=>OverViewHelpers::get_place_name_from_id($each_user["placeId"] ?? ""),
            // 現在のstatusを加える
            "status"=>OverViewHelpers::get_register_status($each_user->id,$model_name) ? "済" : "未",
            // roleを加える
            "role"=>$role_name]);
            $carry=[...$carry,...$all_user_data_with_role->toArray()];
            return $carry;
        },[]);
    }

    // 登録された場所の情報を取得
    private static function get_all_places(){
        return (Place::select("id","place_name","red","green","blue")->get())->map(fn($each_data)=>[...$each_data->toArray(),"branch_manager_lists"=>OverViewHelpers::get_branch_manager_lists($each_data["id"])]);
    }

    // userのキーを日本語名で返却(テーブル用)
    public static function get_user_key_name(){
        return[
            "user_name"=>"ユーザー名",
            "role"=>"職種",
            "status"=>"本登録",
            "place_name"=>"営業所名",
            "staff_name"=>"スタッフ名"
        ];
    }
    // placeのキーを日本語名で返却(テーブル用)
     public static function get_place_key_name(){
        return[
            "place_name"=>"営業所名",
            "branch_manager_lists"=>"担当者リスト"
        ];
    }
}

