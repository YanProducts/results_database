<?php
// 配布済データのモデルヘルパー
namespace App\Support\Common\ModelHelpers;
use App\Models\DistributionRecord;

class DistributionRecordHelpers{
    // その日、そのスタッフのデータを返す
    public static function data_in_the_date_and_staff($date,$staff){
        return DistributionRecord::where("staff_id",$staff)->where("distribution_date",$date)->get();

    }

    // そのスタッフの、該当期間におけるデータを返す(操作しやすいように期日ごとにgroupByは行わない)
    // date_setsはY-m-d=>日本語の配列なので、array_keysが必要
    public static function data_in_the_range_and_staff($date_sets,$staff){
        return DistributionRecord::where("staff_id",$staff)->whereIn("distribution_date",array_keys($date_sets))->get();

    }


    // 該当するplanのidの配列から「プランid、配布数、日時、スタッフ」の配列で返し、プランidごとにまとめる
    public static function get_record_sets_in_the_plan_ids_group_by_plan_ids($plan_ids){
       return DistributionRecord::whereIn("plan_id",$plan_ids)->select("id","plan_id","distribution_date","staff_id","distribution_count")->get()->groupBy("plan_id");
    }
}
