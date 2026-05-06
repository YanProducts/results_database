<?php
// 配布済データのモデルヘルパー
namespace App\Support\CommonModelHelpers;
use App\Models\DistributionRecord;

class DistributionRecordHelpers{
    // その日、そのスタッフのデータを返す
    public static function data_in_the_date_and_staff($date,$staff){
        return DistributionRecord::where("staff_id",$staff)->where("distribution_date",$date)->get();

    }
}
