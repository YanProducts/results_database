<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionPlan extends Model
{
    // 配布計画のモデル
    // same_project_flugは同じプロジェクトで応援や役割分割や大きい町目で担当者が複数存在する時のフラグ
    protected $fillable=[
        "projectId","same_project_flug","placeId","start_date","end_date","addressesId","remark_from_operator"
    ];
}
