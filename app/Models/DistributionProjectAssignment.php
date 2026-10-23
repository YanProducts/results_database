<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionProjectAssignment extends Model
{
    // 割り当ての案件用のモデル
    protected $fillable = [
        "project_plan_id","staff_id","date","end_date","status","from_simple_flag","created_by"
    ];
}
