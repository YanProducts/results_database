<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionAssignment extends Model
{
    // 営業所でスタッフに分割が終えたもの
    protected $fillable=[
        "plan_id","staff_id","date","end_date","status"
    ];
}
