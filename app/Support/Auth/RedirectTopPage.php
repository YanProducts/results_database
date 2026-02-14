<?php
namespace App\Support\Auth;

use App\Enums\UserRole;
use App\Exceptions\BusinessException;

// 認証決定後、それぞれのトップページへのルート名称を返す
// whole_dataを含む
class RedirectTopPage{
    public static function  redirect_top_page($current_route_name){
        // whole_dataのとき
        if(str_contains($current_route_name,"whole_data")){
            return "whole_data.top";
        }
        // whole_data以外の時
        try{
            return UserRole::top_page_route_name($current_route_name);
        }catch(\UnhandledMatchError $e){
        throw new BusinessException("このページに来る経路のエラーです");
        }

    }
}
