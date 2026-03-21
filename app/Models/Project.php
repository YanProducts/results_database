<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //案件データのモデル
    // another_project_flagは同じ案件名で違う案件のフラグ
    protected $fillable=[
        "created_by","start_date","end_date","project_name","another_project_flag"
    ];
}
