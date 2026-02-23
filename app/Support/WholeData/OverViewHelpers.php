<?php
// 全体を返すデータにおける細かいメソッド一覧
namespace App\Support\WholeData;

use App\Models\BranchManagerList;
use App\Models\Place;
use App\Models\UserAuth;

class OverViewHelpers{

    // 事前登録されたユーザーは本登録されているかを取得
    public static function get_register_status($id,$model_name){
      return UserAuth::where([["authable_id",$id],["authable_type",$model_name]])->exists();
    }

    // データを取得するqueryを返す
    public static function get_querys($role_name){

        $select_lists=["id as role_id","user_name"];

        if($role_name=="field_staff"){
            $select_lists[]="staff_name";
        }

        if(in_array($role_name,["branch_manager","field_staff"])){
            $select_lists[]="placeId";
        }

        // selectには配列を渡しても良い
        return $select_lists;

    }

    // 営業所のidから営業所名を返す
    public static function get_place_name_from_id($place_id){
        if(empty($place_id)){
            return "-";
        }
        return Place::where("id",$place_id)->value("place_name");
    }

    // その営業所の担当者名を一覧で返す
    public static function get_branch_manager_lists($place_id){
        return implode("、",BranchManagerList::where("placeId",$place_id)->pluck("user_name")->toArray());
    }

    // どのタイプかの日本語名(ページタイトルに使用)
    public static function get_page_type_name($type){
        return match($type){
            "all"=>"すべて",
            "user"=>"ユーザー",
            "place"=>"営業所",
             default=>""
        };
    }

}

