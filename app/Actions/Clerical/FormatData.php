<?php

// UI表示やその他の事情に合わせて、データを計算・整理して並び替える
namespace App\Actions\Clerical;

use App\Exceptions\BusinessException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FormatData{

    // 今あるデータの初期ページ用の変換
    public static function data_change_for_management_page($project_sets,$town_counts,$recorded_counts){

        $projects_in_sql=[];
        foreach($project_sets as $project_id=>$project_data){

            // 配布済みのデータ(ない場合はnull)
            $recorded_data=$recorded_counts[$project_id] ?? null;

            $projects_in_sql[$project_id]=[
                "project_name"=>$project_data["project_name"],
                "end_date"=>Carbon::parse($project_data["end_date"])->format("n月j日"),
                // 割り当てで振られた町目数
                "planned_town_counts"=>$town_counts[$project_id]["planned_town_counts"] ?? 0,
                // 入力済の町目数
                "recorded_town_counts"=>$recorded_data["recorded_town_counts"] ?? 0,
                // 入力済の配布合計数
                "recorded_distribution_counts"=>$recorded_data["sum_distribution_counts"] ?? 0,
                // 案件が入力が終了しているか
                "is_complete"=>$project_data["is_complete"]

            ];
        }
        return $projects_in_sql;
    }

    //CSV出力用の改変
    public static function data_change_for_csv_report_data($plans_in_project_ids,$project_sets,$address_sets,$distribution_record_sets){

        $formatted_distribution_data=$distribution_record_sets->mapWithKeys(fn($each_set,$plan_id)=>
            [$plan_id=>[
            "distribution_counts"=>$each_set->pluck("distribution_count")->sum(),
            "staff_ids"=>implode("、",$each_set->pluck("staff_id")->toArray()),
            "distribution_dates"=>implode("、",$each_set->pluck("distribution_date")->toArray())
            ]
        // 後にスプレッド展開するので配列に直す
        ])->toArray();

        //１：上記のplan_idに対応する各配布枚数、２：上記のaddress_idに対応する各町目名を取得
        // mapと後のgrouoByはcollection特有なので外側はコレクションのまま
        $formatted_data=$plans_in_project_ids->map(fn($each_plan)=>[
            // // コレクションはスプレッド展開できない
            // ...$each_plan->toArray(),
            // プロジェクト名
            "project_name"=>$project_sets[$each_plan["project_id"]],
            // 住所は必ず存在、なければエラー
            "address_name"=>$address_sets[$each_plan["address_id"]],
            ...$formatted_distribution_data[$each_plan["id"]] ?? [
                "distribution_counts"=>0,
                "staff_ids"=>"未配布",
                "distribution_dates"=>"未配布"
            ]
        ]);

        // 上記を配列化して返す
        return $formatted_data->toArray();
    }


}
