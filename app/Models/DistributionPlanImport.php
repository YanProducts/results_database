<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionPlanImport extends Model
{
    //配布計画の前段階、町目重なり確認の一時保存用のモデル
    protected $fillable = [
        "project_id","place_id","start_date","end_date","address_id","remark_from_operator","distribution_plan_id","distribution_record_id"
    ];
}
