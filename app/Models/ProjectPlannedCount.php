<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPlannedCount extends Model
{
    // その日に営業所ごとに振られた案件ごと設定部数のモデル
    protected $fillable=[
        "project_id","place_id","round_number","main_id","project_planned_counts","start_date","end_date"
    ];
}
