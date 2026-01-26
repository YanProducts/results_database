<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //住所のモデル
    protected $fillable=[
        "pref","city","town","household","apartment","detached","establishment"
    ];
}
