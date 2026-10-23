<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionProjectAssignmentImport extends Model
{
    // 案件ごとに割り当てを行った時の重複確認
    protected $fillable = [
        "plan_id","staff_id","date","end_date","created_by","from_simple_flag","created_by"
    ];
}
