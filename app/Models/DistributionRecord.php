<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PropertyHookType;

class DistributionRecord extends Model
{
    //町丁目ごとの配布部数のデータ
    protected $fillable=[
        "distribution_date","distribution_count","address_id","project_id","staff_id","remark","plan_id"
    ];

    // リレーション(これをしておくことで、with(モデル名)とすれば、そのモデルの該当idが取得でき、そこから各カラムの値を取得できる)
    // ->projectというメソッドを呼べば、Projectモデルを取得、その値を取得でき、参照するidがconstrainedでリレーションで結びつけた値(結びつけるid)
    public function project(){
        return $this->belongsTo(Project::class,"project_id");
    }
}
