<?php

namespace App\Support\Auth\whole_data;

use App\Models\OnetimeWholeData;
use App\Exceptions\BusinessException;

// onetimetokenを設定した仮登録データにおいて、メールパラメータとtokenの値とtokenの保存期限とを検証し、一致しないもしくは切れているものはエラーを投げる
class WholeDataOnetimeCheck{
    public static function token_check($token){
        try{
            $onetime_whole_data_model=new OnetimeWholeData;
            $onetime_whole_data=$onetime_whole_data_model->query()->firstOrFail();
            if($onetime_whole_data->onetime_token!==$token){
                throw new BusinessException("トークンの値が違います");
            }
        }catch(\Throwable $e){
            // 外部に投げる
            throw new BusinessException("データが存在しない、もしくはアクセス権がありません");
        }
    }
}
