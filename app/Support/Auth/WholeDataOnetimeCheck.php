<?php

namespace App\Support\Auth;

use App\Models\OnetimeWholeData;

// onetimetokenを設定した仮登録データにおいて、メールパラメータとtokenの値とtokenの保存期限とを検証し、一致しないもしくは切れているものはエラーを投げる
class WholeDataOnetimeCheck{
    public static function token_check($token){
        try{
            $onetime_whole_data=new OnetimeWholeData()->query()->firstOrFail();
            if($onetime_whole_data->onetime_token!==$token){
                throw new \Error("トークンの値が違います");
            }
        }catch(\Throwable $e){
            // 外部に投げる
            throw new \Error("データが存在しない、もしくはアクセス権がありません");
        }
    }
}
