<?php

namespace App\Actions\Clerical\PurchaseOrder;

use App\Exceptions\BusinessException;
use App\Models\DistributionRecord;
use App\Utils\DateHelper;
use Illuminate\Support\Facades\Log;

// 発注書作成におけるSQLデータの取得
class GetDataInSql{
    public static function get_data_in_sql($staff_id,$start_date,$end_date){
        try{
            // 「〜ヶ月前」で補足したdateから、開始日における最初の日と終了日のおける最後の日の取得
            [$start_month_date,$end_month_date]=DateHelper::get_month_range($start_date,$end_date);

            // 対象のスタッフ、対象の月日のデータを、同じ日付をキーにしてまとめた入れ子配列で取得
            // 日付と案件名の両方でgropuByして日付の若い順に並べる
            return DistributionRecord::where("staff_id",$staff_id)->whereBetween("distribution_date",[$start_month_date,$end_month_date])->with("project")->get()->groupBy(fn($row)=>[$row->distribution_date."|".$row->project->project_name])->sortKeys();

        }catch(\Throwable $e){
            throw new BusinessException("データ取得時のエラーです");
        }
    }

}
