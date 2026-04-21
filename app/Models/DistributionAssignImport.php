<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionAssignImport extends Model
{
    //スタッフにデータを送った際の重複確認
    protected $fillable = [
        "plan_id","staff_id","date","end_date","created_by"
    ];
}
