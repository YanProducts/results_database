<?php

namespace App\Actions\BranchManager\Report;

use App\Actions\Shared\PrepareForStoreDistributionRecord;
use App\Models\DistributionAssignment;
use App\Models\DistributionRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// 報告書を編集したものを保存する
class StoreEditedReports{
    // 編集して保存する一連の流れ
    public static function store_edited_reports($date,$report_data,$staff_id){

            [$assign_ids,$insert_array]=PrepareForStoreDistributionRecord::prepare_for_store($date,$report_data,$staff_id,true);

            DB::transaction(function()use($insert_array,$assign_ids){
                // そのスタッフ、その日付、そのプランId(assign_idから取得)に対応するデータ(必ず1つ以内)があれば更新、なければ登録
                DistributionRecord::upsert($insert_array,uniqueBy:["distribution_date","address_id","plan_id"],update:["distribution_count"]);

               // assignmentのstatusの変更
                DistributionAssignment::whereIn("id",$assign_ids)->where("status","<>",1)->update(["status"=>1]);
            });



            }
}
