<?php

// 日毎の案件一覧において、取得したSQLからのデータを、JSXの出力にあう形式に変更
namespace App\Actions\ProjectOperator\Management;

use Illuminate\Support\Facades\Log;

class OverviewByDayFormatter{

    // 日毎のデータをJSXのフォーマットに合わせて変更
    public static function change_data_for_overview_by_day($plans_and_counts,$project_name_corresponds_id,$place_name_corresponds_id,$distribution_plans,$city_name_corresponds_id){

        // サブ案件とメイン案件のセット
        $main_sub_sets=(clone $plans_and_counts)->select("project_id","main_id")->where("main_id","<>",null)->groupBy("main_id");
        // 階層を変換
        $plans_by_items=(clone $plans_and_counts)->where("main_id",null)->groupBy(["start_date","place_id",function($item){return ($item["project_id"]."|".$item["round_number"]);}]);

        // フォーマット変更したものを返す
        return  $plans_by_items->mapWithKeys(fn($data_by_start_date,$start_date)=>
                  [$start_date     // 開始日
                    =>$data_by_start_date->mapWithKeys(fn($data_by_place_id,$place_id)=>
                     [$place_name_corresponds_id[$place_id]=>  //営業所を名前に変換
                         $data_by_place_id->mapWithKeys(function($data_by_project,$project_id_with_round_number)use($project_name_corresponds_id,$main_sub_sets,$distribution_plans,$start_date,$place_id,$city_name_corresponds_id){ //案件でグループ分け(round_number込み)
                         //後の検索用にproject_idとround_numberを取得し、表示用にproject_nameを取得(round_numberは0以外は()で入れて、そうでないものはproject_nameを取得)
                         [$project_id,$round_number,$project_name]=self::get_project_and_round_number_for_search_and_view($project_id_with_round_number,$project_name_corresponds_id);

                         //上記のセットにより、その案件は一意に決まるので、必ずdata_by_projectの配列の長さは0になる(1以上でエラー出すか？検索だけなら痛手も少なく不必要？？)
                         $data_sets=$data_by_project[0];

                        return [$project_name=> //round_numberを消した案件名を変更
                          [
                            "sub_sets"=>(($main_sub_sets[$data_sets["id"]]->pluck("project_id"))->map(fn($each_sub_project_id)=>$project_name_corresponds_id[$each_sub_project_id]))->implode("、"), //併配案件
                            "city_lists"=>self::get_city_name_lists_for_view($distribution_plans,$start_date,$place_id,$project_id,$round_number,$city_name_corresponds_id),//市のリスト
                            "end_date"=>$data_sets["end_date"],//終了日
                            "counts"=>$data_sets["counts"],//合計
                            ]];
                          })//案件ごと
                        ])//営業所ごと
                    ]);//開始日ごと
     }

    //表示用&検索用のプロジェクト名の取得
    public static function get_project_and_round_number_for_search_and_view($project_id_with_round_number,$project_name_corresponds_id){
                // project_nameとround_numberの両方で配列は区切られているので、そこを切り取り別々にする
                $project_id_with_round_number_array=explode("|",$project_id_with_round_number);
                $project_id=$project_id_with_round_number_array[0];
                $round_number=intval($project_id_with_round_number_array[1]) ?? "";
                // 表示用の案件名を取得(round_numberから何回目かも求める)
                $project_name=$project_name_corresponds_id[$project_id].(empty($round_number) ? "" : "(".($round_number+1).")");

            // 順にprojectのid、round_number、表示用のproject名
            return [$project_id,$round_number,$project_name];
    }

    //  市のリスト(planから開始日、営業所、メイン案件、round_number(same_project_flag)が等しい案件を取り出し、そのaddress_idを取得）
    //  定義の問題で、現状はsame_project_flagが意図通りは捕捉されていない可能性
    public static function get_city_name_lists_for_view($distribution_plans,$start_date,$place_id,$project_id,$round_number,$city_name_corresponds_id){
        return ($distribution_plans->where("start_date",$start_date)->where("place_id",$place_id)->where("project_id",$project_id)->where("same_project_flag",$round_number)->pluck("address_id"))->map(fn($each_address_id)=>$city_name_corresponds_id[$each_address_id])->unique()->implode("、");
    }

}
