<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
        //場所のモデル
        protected $fillable=[
            "user_name","placeId","place_name"
        ];
}
