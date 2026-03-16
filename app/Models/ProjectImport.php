<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectImport extends Model
{
    //案件が重なっていてるものを含むCSVが投稿された時の一時保存用
    protected $fillable=[
        "created_by","start_date","end_date","project_name","place_id","another_project_flag","project_id","change_flag"
    ];

}
