<?php

namespace App\Actions\Clerical\DataManagement\WriteReport;

use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Support\Common\ModelHelpers\PlaceHelpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// そのプロジェクトに対する配布データの取得
// 町目分割で営業所を分ける場合はplan_idがそもそも別
// planIn=>[町目、営業所、(配布後の)スタッフ名、(配布後の)部数、(配布後の)日付]で取得
// スタッフと配布部数と日付は複数人or複数日で配布している場合もあるので入れ子にする
class GetDistributionDataInTheProjects{
    // 報告書データを取得する流れ
    public static function get_data_for_editting_report_by_the_project($project_id){

        // sqlから情報をとってくる
        [$planned_data_sets,$address_household_sets,$place_sets,$recorded_sets,$staff_name_sets]=self::get_data_in_sql($project_id);

        // 上記に書いた形に並び替える
        return self::get_sorted_date($planned_data_sets,$address_household_sets,$place_sets,$recorded_sets,$staff_name_sets);
    }

    // sqlから取得
    public static function get_data_in_sql($project_id){
        // そのプロジェクトIdに該当するplanされた町目(projectId=>[planId,営業所,町目]の配列)
        // projectの名前が一緒でanother_projectの場合はそもそもprojectIdが違うので考慮しなくても良い
        $planned_data_sets=DistributionPlanHelpers::get_planned_data_by_the_project_id($project_id);

        // 住所の名前一覧を返す(id=>住所の連想配列で返る)
        $address_household_sets=AddressHelpers::get_address_name_and_household_set_arrays_key_by_id($planned_data_sets->pluck("address_id")->unique());

        // 営業所の名前一覧を返す(id=>営業所の名前)
        $place_sets=PlaceHelpers::get_place_name_from_ids($planned_data_sets->pluck("place_id")->unique());
        // 該当planに対応する配布結果(複数人が行っている場合があるので、入れ子の配列で取得)
        $recorded_sets=DistributionRecordHelpers::get_record_sets_in_the_plan_ids_group_by_plan_ids($planned_data_sets->pluck("plan_id"));
        $staff_name_sets=FieldStaffListHelpers::get_staff_names_from_id_array(($recorded_sets->collapse())->pluck("staff_id"));

        return[$planned_data_sets,$address_household_sets,$place_sets,$recorded_sets,$staff_name_sets];
    }

    // データの並び替え
    public static function get_sorted_date($planned_data_sets,$address_household_sets,$place_sets,$recorded_sets,$staff_name_sets){

        // plan_id=>町名、営業所名、[スタッフ、日付、配布数]
        $filtered_data=$planned_data_sets->mapWithKeys(function($each_plan_data)use($place_sets,$address_household_sets,$recorded_sets,$staff_name_sets){
            $plan_id=$each_plan_data->plan_id;
            $records_by_plan_id=$recorded_sets[$plan_id] ?? collect(); //plan_id(つまり、この案件における町目)における現在配布後のデータセット
            $address_set_key_by_id=$address_household_sets[$each_plan_data->address_id];

            // records_by_plan_idの中に、配列でスタッフ、日付、部数などが入ってる
            return[$plan_id=>[
                "town_name"=>$address_set_key_by_id["city"].$address_set_key_by_id["town"],
                "house_hold"=>$address_set_key_by_id["household"], //世帯数
                "place_name"=>$place_sets[$each_plan_data->place_id], //営業所名
                "total_counts"=>$records_by_plan_id->pluck("distribution_count")->sum(), //現在配布部数
                "detail_data"=>self:: get_detail_data($records_by_plan_id,$staff_name_sets)
            ]];
        });


        // 町目が同じplan_idは異なるものも1つにまとめる（1つの町目を2つの案件で分割して行ったとき）
        // 表示用なので、plan_idは最も小さいものに合わせる。detail_dataは合わせる
        // 最終的にこのデータを返す
        return self::aggregated_by_town($filtered_data);;


    }

    // その町目における詳細データの取得
    // 調整データは合計でまとめる
    public static function get_detail_data($records_by_plan_id,$staff_name_sets){

        // 調整データの分のみ取り出し、合計を求める
        $unknown_data_sum=($records_by_plan_id->filter(fn($each_record_for_unknown)=>empty($each_record_for_unknown["staff_id"])))->pluck("distribution_count")->sum();

        // スタッフidが存在する時は、それぞれのデータを出す
        $staff_data_lists=($records_by_plan_id->filter(fn($each_record_for_staff_data)=>!empty($each_record_for_staff_data["staff_id"])))->map(fn($each_record_by_staff)=>
            "スタッフ：".$staff_name_sets[$each_record_by_staff["staff_id"]]."、日付：".Carbon::parse($each_record_by_staff["distribution_date"])->format("n月j日")."、部数：".$each_record_by_staff["distribution_count"]
            //スタッフ・日付・部数のセット(複数人の場合は複数行で文字列として繋げる)
        );

        return (!empty($unknown_data_sum) ? $staff_data_lists->push("入力担当記入部数：".$unknown_data_sum):$staff_data_lists)->implode("\n");
    }

    // 同じ町目を1つにまとめる(例：大きな町を案件担当が初期の状態で2つ分割して行ったとき)
    // 表示用なのでplan_idは最後のものだけでOK(編集した入力担当記入部数を、次回編集時に最後に表示させるため)
    public static function aggregated_by_town($base_filtered_data){

        // 一時的にtown_nameをキーにとった構造に変化(要素の数はtown_nameを元にしたものに変わる)
        // それをmapWithkeysでplan_idをキーにしたものに変える
        return $base_filtered_data
            ->groupBy('town_name',preserveKeys: true)
            ->mapWithKeys(function ($plan_data_with_the_town, $town_name) {

            // 最後のplan_idをキーに、まとめた値を格納して返す plan_idが重複なしと重なることはありえない(町目が一意のため)
            $plan_id = $plan_data_with_the_town->keys()->last();

            return [
                $plan_id => [
                    'town_name' => $town_name,
                    'house_hold' => $plan_data_with_the_town->last()['house_hold'],//必ず同じ
                    'place_name' => $plan_data_with_the_town->pluck('place_name')->unique()->sort()->implode('・'), // 営業所名の文章(同じ営業所は1つにまとめる)
                    'total_counts' => $plan_data_with_the_town->sum('total_counts'),
                    'detail_data' => $plan_data_with_the_town->pluck('detail_data')->implode("\n"),//uniqueになることは営業所が違うスタッフ名が偶然いて日付と部数も同じ時に存在だが、可能性が極小なことと、それをまとめても合計が合わなくなるため、そのまま別のケースとして保存,
                ]
            ];
        });

    }


}
