<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchManagerList extends Model
{
    //営業所担当のモデル
    protected $fillable=[
        "user_name","placeId"
    ];

}
