<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldStaffList extends Model
{
       //スタッフのモデル
       protected $fillable=[
        "user_name","staff_name","placeId"
    ];
}
