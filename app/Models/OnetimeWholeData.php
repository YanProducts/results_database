<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnetimeWholeData extends Model
{
     // Dataが不可算名刺なので、自動推論だとズレる場合があるので指定する
    protected $table = 'onetime_whole_datas';

    //全体統括者の仮保存データ(メールアドレスから本登録)
    protected $fillable=["user_name","password","onetime_token","expired_at"];
}
