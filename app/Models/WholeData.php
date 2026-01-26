<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholeData extends Model
{
    // 一括代入
    protected $fillable=[
        "user_name","password",
    ];

    // 外に出すときに隠す
    protected $hidden=[
        "password","remember_token"
    ];
}
