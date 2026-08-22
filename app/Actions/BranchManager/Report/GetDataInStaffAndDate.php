<?php
// 営業所版の、その日&そのスタッフのデータの検索

namespace App\Actions\BranchManager\Report;
use App\Actions\FieldStaff\WriteReport\GetDataInStaffAndDate as DataFromFieldStaffs;
use App\Exceptions\BusinessException;
use App\Models\DistributionAssignment;
use App\Models\DistributionPlan;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Utils\DateHelper;
use Illuminate\Support\Facades\Log;

class GetDataInStaffAndDate{
    // スタッフがその日に割り当てられた&配布したデータの取得
    public static function get_assigned_and_recorderd_data($date,$staff){

        // そのスタッフに割り当てられた案件
        $assigns=DataFromFieldStaffs::get_assigned_or_recorded_data($staff,DateHelper::change_key_value_set($date),true,false);

        // 日が決まっているので、assignの値は一意に決まる
        if(count($assigns)!=1){
            throw new BusinessException("割り当て案件取得のエラーです");
        }


        Log::info($assigns);

        // 上記の割り当てに当てはまる結果の参照
        // assignのデータは一意に決まっている
        $records=self::get_record_data_matched_assigns($assigns[0],array_keys($assigns)[0],$staff);


        $assigns_with_records="";


        return [$assigns_with_records];
    }

    // その割り当て
    public static function get_record_data_matched_assigns($assigns,$date,$staff){

            // メイン案件のassign_idの取得
            $assign_ids=$assigns->map(fn($data_by_project)=>$data_by_project["each_project_data"]->map(fn($each_town_data)=>$each_town_data["assign_id"]))->flat();

            // assign_idに入っているからplan_idのコレクションを取得(後にさまざまな形で使用するのでgetにしておく。N+1を考慮してqueryのままにはしない)
            $assigns_matched_plan_id=DistributionAssignment::select("id","plan_id")->whereIn("id",$assign_ids)->get();

            // 上記のassignに見合うplanの取得
            $plan_ids=$assigns_matched_plan_id->pluck("plan_id");

            // 上記のidを持つplan、もしくは上記をmain_idに持つplanを取得
            $plan_data=DistributionPlan::whereIn("id",$plan_ids)->orWhereIn("main_id",$plan_ids)->get()->keyBy("id");


            // その日、その案件に
            $records_in_sql=DistributionRecordHelpers::data_in_the_date_and_staff($date,$staff);
            foreach($assigns as $project_name=>$assign_data_by_project){
                Log::info("案件名".$project_name);
                Log::info("日付".$date);
                Log::info("スタッフId".$staff);
                Log::info("併配セット");
                Log::info($assign_data_by_project["project_set"]);
                Log::info("町目データ.メイン案件のデータ。ここの内部にサブ案件がある");
                //ここにcountsを足す
                Log::info($assign_data_by_project["each_data"]);
                // メイン案件の町目ごとのデータセット
                foreach($assign_data_by_project["each_data"] as $each_assigned_main_town_data){
                    // assignテーブルでのid
                    Log::info($each_assigned_main_town_data["assign_id"]);
                    // ここでのplanをassign_idを元に検索する（前もって行うこと） //whereInの範囲を選ぶこと
                     $assigns_matched_plan_id->where("id",$each_assigned_main_town_data["assign_id"])->value("plan_id");
                    // 上記のplanに合うcountをrecordから検索（それがメイン案件）
                    $record_in_the_town=$records_in_sql->where("plan_id",$assigns_matched_plan_id);

                    // 上記のplanの中でmain_idががそのidのもの



                    // countsとplan_countsを足す
                }

                // $records_in_sql->where("distribution_date",$date)->("staff_id",$staff)->("")
            }

    }
}
