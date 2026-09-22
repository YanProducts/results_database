<?php

// 案件ごとの配布結果を変更する
namespace App\Actions\Clerical\DataManagement\WriteReport;

use App\Exceptions\BusinessException;
use App\Models\DistributionRecord;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChangeDistributionDataByProject{

    // データの変更の流れ
    public static function change_data_procedure($project_id,$changed_difference){
        $date=Carbon::now(); //現在の日付
        $address_by_plan=self::get_all_addresses_within_plan_ids($changed_difference); //planのidをキーにした住所idの取得
        // distribution_recordにplanIdとcountDifferenceから値をセットしていく。project_idは取得済み、address_idはplan_idより取得
        $all_insert_data=[];
        foreach($changed_difference as $each_data){
            $all_insert_data[]=[
                "distribution_date"=>null, //入力担当から編集の時はnull
                "distribution_count"=>$each_data["countDifference"], //差分をそのまま入力
                "address_id"=>$address_by_plan[$each_data["planId"]],
                "project_id"=>$project_id,
                "staff_id"=>null, //入力担当からの編集の時はnull
                "remarks"=>"",
                "created_at"=>$date,
                "updated_at"=>$date,
                "plan_id"=>$each_data["planId"],
                "is_unknown"=>true
            ];
        }
        // 挿入
        try{
            DB::transaction(function()use($all_insert_data){DistributionRecord::insert($all_insert_data);});
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            throw new BusinessException("データ変更時にエラーが発生しました");
        }
    }

    // planごとの住所idを一括で取得
    public static function get_all_addresses_within_plan_ids($changed_difference){
        // 該当のplan_idを配列化
        $plan_ids=array_column($changed_difference,"planId");
        // セットで取得
        return DistributionPlanHelpers::get_address_ids_key_by_plan_ids($plan_ids);
    }

}
