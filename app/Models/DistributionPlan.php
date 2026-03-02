<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionPlan extends Model
{
    // 配布計画のモデル
    protected $fillable=[
        "projectId","same_project_flug","placeId","start_date","end_date","addressesId","remark_from_operator"
    ];
}
