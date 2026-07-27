<?php
namespace App\Actions\Clerical\PurchaseOrder;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Log;

// 発注書作成における書式の変換
class FormatData{

    //発注書作成におけるデータの変更
    public static function data_change_for_purchase_order($staff_name,$start_month_name,$end_month_name,$record_of_staff_and_date_change){
        try{
            //１行目=スタッフ名
            $return_array[]=[$staff_name];
            //２行目=開始月：同じこともあり
            $return_array[]=[$start_month_name];
            //３行目=終了月：同じこともあり
            $return_array[]=[$end_month_name];

            //４行目以下＝日付、案件名、枚数の順でデータを入れる
            //取得したSQLデータは日付でgroupByされている
             $return_array=
            [...$return_array,...$record_of_staff_and_date_change->map(function($data_grouped_by_date_and_projects){

                return[
                    // 日付と案件名
                    // 日付と案件名でgroupByされているので、日付と案件名は配列ですべて同じ。
                    $data_grouped_by_date_and_projects[0]->distribution_date,
                    $data_grouped_by_date_and_projects[0]->project->project_name,
                    // 枚数
                    $data_grouped_by_date_and_projects->sum("distribution_count")
                ];
            })->toArray()];
        }catch(\Throwable $e){
            // 呼び出し元にthrow
            throw new BusinessException("データ変更時のエラーです");
        }

        return $return_array;
    }

}
