<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPlannedCountImport extends Model
{
    //案件ごとの設定部数の投稿時の重複時の一時保存
    protected $fillable=[
        "created_by","project_name","place_id","main_id","counts","start_date","end_date"
    ];
}
