<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionRecord extends Model
{
    //町丁目ごとの配布部数のデータ
    protected $fillable=[
        "distribution_date","distribution_count","address_id","project_id","staff_id","remark"
    ];
}
