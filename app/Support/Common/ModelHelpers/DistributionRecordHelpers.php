<?php
// 配布済データのモデルヘルパー
namespace App\Support\Common\ModelHelpers;
use App\Models\DistributionRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DistributionRecordHelpers{

    //  すでにデータが存在しているかの確認(町目重複の可能性)
    public static function data_is_exists($project_id,$address_id){
        return
            DistributionRecord::where([
                ["project_id",$project_id],
                ["address_id",$address_id]
            ])->exists();
    }


        // プロジェクトと住所のidから、結果テーブルのidを返す
        public static function get_id_from_project_and_address($project_id,$address_id){
            return
                DistributionRecord::where([
                    ["project_id",$project_id],
                    ["address_id",$address_id]
                ])->value("id");
        }


        // 複数の住所と案件のIDから、その案件において既に計画済みの町目idを返す
        public static function get_address_ids_in_the_projects_in_sql($project_id,$address_ids){
            return DistributionRecord::whereIn("address_id",$address_ids)->where("project_id",$project_id)->pluck("address_id");
        }



    // その日、そのスタッフのデータを返す
    public static function data_in_the_date_and_staff($date,$staff){
        return DistributionRecord::where("staff_id",$staff)->where("distribution_date",$date)->get();

    }

    // そのスタッフの、該当期間におけるデータを返す(操作しやすいように期日ごとにgroupByは行わない)
    // date_setsはY-m-d=>日本語の配列なので、array_keysが必要
    public static function data_in_the_range_and_staff($date_sets,$staff){
        return DistributionRecord::where("staff_id",$staff)->whereIn("distribution_date",array_keys($date_sets))->get();
    }

    // 何人かのスタッフにおける、該当期間におけるデータを返す(操作しやすいように期日ごとにgroupByは行わない)
    // date_setsはY-m-d=>日本語の配列なので、array_keysが必要
    public static function data_in_the_range_and_staffs($date_sets,$staff_ids){
        return DistributionRecord::whereIn("staff_id",$staff_ids)->whereIn("distribution_date",array_keys($date_sets))->get();
    }


    // 該当するplanのidの配列から「プランid、配布数、日時、スタッフ」の配列で返し、プランidごとにまとめる
    public static function get_record_sets_in_the_plan_ids_group_by_plan_ids($plan_ids){
       return DistributionRecord::whereIn("plan_id",$plan_ids)->select("id","plan_id","distribution_date","staff_id","distribution_count")->get()->groupBy("plan_id");
    }

    // 「〜年前」という形式できた場合において、該当期間における全部のデータのクエリの取得
    // 呼び出し先で別の項目を絞ってsql呼び出しできるよう、クエリのみ取得する
    // is_start_infinitがtrueのときは、開始年度は無制限
    public static function get_query_of_all_records_by_year_range($start_year_range,$end_year_range,$is_start_infinit=false){
        if(!$is_start_infinit){
            $start_date=Carbon::now()->subYear($start_year_range);
            $end_date=Carbon::now()->subYear($end_year_range);
            return DistributionRecord::whereBetween("distribution_date",[$start_date,$end_date]);
        }else{
            return DistributionRecord::where("distribution_date","<",Carbon::now()->subYear($end_year_range));
        }
    }

}
