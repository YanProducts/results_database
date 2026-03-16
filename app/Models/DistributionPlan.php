<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionPlan extends Model
{
    // 配布計画のモデル
    // same_project_flagは同じプロジェクトで応援や役割分割や大きい町目で担当者が複数存在する時のフラグ
    protected $fillable=[
        "created_by","project_id","same_project_flag","place_id","start_date","end_date","address_id","remark_from_operator"
    ];
}
