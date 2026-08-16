<?php

// 取得したSQLからのデータを、JSXの出力にあう形式に変更
namespace App\Actions\ProjectOperator\Management;

use Illuminate\Support\Facades\Log;

class OverviewByDayFormatter{

    public static function change_data_for_overview_by_day($plans_and_counts,$project_name_corresponds_id,$place_name_corresponds_id,$distribution_plans,$city_name_corresponds_id){

        // Log::info($plans_and_counts->groupBy(["start_date","place_id","project_id","round_number"]));
        // Log::info($project_name_corresponds_id);
        // Log::info($place_name_corresponds_id);
        // Log::info($distribution_plans);
        // Log::info($city_name_corresponds_id);

        // サブ案件とメイン案件のセット
        $main_sub_sets=(clone $plans_and_counts)->select("project_id","main_id")->where("main_id","<>",null)->groupBy("main_id");


        // 階層を変換
        $plans_by_items=(clone $plans_and_counts)->where("main_id",null)->groupBy(["start_date","place_id",function($item){return $item["project_id"]."|".$item["round_number"];}]);


        // フォーマット変更
        $format=
        $plans_by_items->mapWithKeys(fn($data_by_start_date,$start_date)=>
            [$start_date     // 開始日
                    =>$data_by_start_date->mapWithKeys(fn($data_by_place_id,$place_id)=>
                     [$place_name_corresponds_id[$place_id]=>  //営業所を名前に変換
                         $data_by_place_id->mapWithKeys(function($data_by_project,$project_id_with_round_number)use($project_name_corresponds_id,$main_sub_sets){ //案件でグループ分け(round_number込み)

                         //project_nameを取得(round_numberは0以外は()で入れて、そうでないものはproject_nameを取得)
                         $project_id_with_round_number_array=explode("|",$project_id_with_round_number);

                         Log::info($project_id_with_round_number);
                         Log::info($project_id_with_round_number_array[1]);

                         $round_number=intval($project_id_with_round_number_array[1]) ?? "";

                         Log::info($round_number);

                         $project_name=$project_name_corresponds_id[$project_id_with_round_number_array[0]].(empty($round_number) ? "" : "(".($round_number+1).")");

                             return [$project_name=> //round_numberを消した案件名を変更
                            $data_by_project->map(fn($each_data)=>[
                                "sub_sets"=>$main_sub_sets[$each_data["id"]], //サブ案件
                                "city_lists"=>"",//市のリスト
                                "end_date"=>$each_data["end_date"],//終了日
                                "counts"=>$each_data["counts"],//合計
                            ])];
                            }
                         )
                     ]
                    )
            ]
        );


Log::info($format->toArray());

        dd(1);


return $format;



    // // メイン案件のみのデータ全てを抽出
    //     $main_distribution_plans=$distribution_plans->where("main_id",null);

    //     // 上記のうち、メイン案件のみの配列を重なりなしで取得(project_idとround_numberをセットで取得しpluck、uniueを使う)

    //     // 上記のうち、サブ案件のみを抽出し、メイン案件=>という形式にして、その内部でuniqueする
    //     $sub_distribution_plans=$distribution_plans->where("main_id","<>",null)->groupBy("main_id");


        // return(
        //     // まずは開始日で分割
        //     $main_distribution_plans->groupBy("start_date")->mapWithKeys(
        //     //さらに内部を営業所名で分割
        //     fn($each_plan_by_start_date,$key1)=>[$key1=>$each_plan_by_start_date->groupBy("place_id")
        //     ->mapWithKeys(fn($each_plan_by_place,$key_by_place)=>[
        //         [
        //          "place_name"=>$place_name_corresponds_id[$key_by_place],//営業所の名前
        //          "place_id"=>$key_by_place, //営業所のid(編集用)
        //          "plan_contents"=>
        //              //この日営業所に振られた仕事の内容
        //               // planをmain案件が同じもの=>round_numberが同じものでまとめ１：併配案件リスト、２：市名リスト、３：最も遅い終了日でまとえる
        //             $each_plan_by_place->groupBy(["project_id","round_number"])->mapWithKeys(fn($each_plan_with_round_key,$key_by_project)=>[
        //               $project_name_corresponds_id[$key_by_project]
        //                   =>$each_plan_with_round_key->mapWithKeys(function($each_plan_by_round,$key_by_round)use($sub_distribution_plans,$city_name_corresponds_id,$project_name_corresponds_id){

        //                     // サブ案件名を一挙取得(sub_listsのキーの元になっているmainのidはつまり、main案件で取得したplanのid)
        //                     $sub_lists=$each_plan_by_round->flatMap(fn($plan)=>
        //                             $sub_distribution_plans
        //                             ->get($plan["id"], collect())
        //                             ->pluck("project_id")
        //                     )->unique()->map(fn($sub_id)=>$project_name_corresponds_id[$sub_id])->implode(",");

        //                     // 市の名前を一挙取得
        //                     $city_lists=$each_plan_by_round->map(fn($each_plan)=>$city_name_corresponds_id[$each_plan["address_id"]])->unique()->implode(",");

        //                     // 最も遅い終了日の取得
        //                     $latest_end_date=$each_plan_by_round->max("end_date");

        //                     return
        //                     [
        //                     $key_by_round=>[
        //                         "sub_lists"=>$sub_lists,
        //                         "city_lists"=>$city_lists,
        //                         "end_date"=>$latest_end_date
        //                     ]];
        //                 })//round_numberでのgroupBy
        //             ])  //メイン案件でのgroupBy
        //     ]])//営業所でのgroupby
        //  ])//日付でのgroupby
        //  );
    }
}
