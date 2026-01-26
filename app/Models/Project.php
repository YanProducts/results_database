<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //案件データのモデル
    protected $fillable=[
        "start_date","end_date","project_name"
    ];
}
