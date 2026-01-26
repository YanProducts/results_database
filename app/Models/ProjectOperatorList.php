<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectOperatorList extends Model
{
       //案件入力担当者のモデル
       protected $fillable=[
        "user_name"
    ];
}
