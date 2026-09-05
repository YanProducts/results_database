<?php

// 報告書で上がってきたデータをSQL登録する
namespace App\Actions\FieldStaff\WriteReport;

use App\Actions\Shared\PrepareForStoreDistributionRecord;
use App\Models\DistributionAssignment;
use Illuminate\Support\Facades\DB;

class StoreAfterDistribution{

    // データ挿入の流れ
    public static function store_report_data_procedure($date,$report_data,$staff_id){

        [$assign_ids,$insert_array]=PrepareForStoreDistributionRecord::prepare_for_store($date,$report_data,$staff_id,false);

        DB::transaction(function()use($insert_array,$assign_ids){
            // 挿入
            DB::table("distribution_records")->insert($insert_array);
            // assignmentのstatusの変更
            DistributionAssignment::whereIn("id",$assign_ids)->update(["status"=>1]);
        });
    }

}
