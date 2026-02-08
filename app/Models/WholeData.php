<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholeData extends Model
{

    // Dataが不可算名刺なので、自動推論だとズレる場合があるので指定する
    protected $table = 'whole_datas';

    // 一括代入
    protected $fillable=[
        "user_name","password,email",
    ];

    // 外に出すときに隠す
    protected $hidden=[
        "password","remember_token"
    ];
}
