<?php

// 取得したSQLからのデータを、JSXの出力にあう形式に変更
namespace App\Actions\ProjectOperator\Management;
class OverviewByDayFormatter{

    public static function change_data_for_overview_by_day($distribution_plans,$project_name_corresponds_id,$city_name_corresponds_id,$place_name_corresponds_id){
        // メイン案件のみのデータ全てを抽出
        $main_distribution_plans=$distribution_plans->where("main_id",null);

        // 上記のうち、メイン案件のみの配列を重なりなしで取得(project_idとround_numberをセットで取得しpluck、uniueを使う)

        // 上記のうち、サブ案件のみを抽出し、メイン案件=>という形式にして、その内部でuniqueする
        $sub_distribution_plans=$distribution_plans->where("main_id","<>",null)->groupBy("main_id");


        return(
            // まずは開始日で分割
            $main_distribution_plans->groupBy("start_date")->mapWithKeys(
            //さらに内部を営業所名で分割
            fn($each_plan_by_start_date,$key1)=>[$key1=>$each_plan_by_start_date->groupBy("place_id")
            ->mapWithKeys(fn($each_plan_by_place,$key_by_place)=>[
                [
                 "place_name"=>$place_name_corresponds_id[$key_by_place],//営業所の名前
                 "place_id"=>$key_by_place, //営業所のid(編集用)
                 "plan_contents"=>
                     //この日営業所に振られた仕事の内容
                      // planをmain案件が同じもの=>round_numberが同じものでまとめ１：併配案件リスト、２：市名リスト、３：最も遅い終了日でまとえる
                    $each_plan_by_place->groupBy(["project_id","round_number"])->mapWithKeys(fn($each_plan_with_round_key,$key_by_project)=>[
                      $project_name_corresponds_id[$key_by_project]
                          =>$each_plan_with_round_key->mapWithKeys(function($each_plan_by_round,$key_by_round)use($sub_distribution_plans,$city_name_corresponds_id,$project_name_corresponds_id){

                            // サブ案件名を一挙取得(sub_listsのキーの元になっているmainのidはつまり、main案件で取得したplanのid)
                            $sub_lists=$each_plan_by_round->flatMap(fn($plan)=>
                                    $sub_distribution_plans
                                    ->get($plan["id"], collect())
                                    ->pluck("project_id")
                            )->unique()->map(fn($sub_id)=>$project_name_corresponds_id[$sub_id])->implode(",");

                            // 市の名前を一挙取得
                            $city_lists=$each_plan_by_round->map(fn($each_plan)=>$city_name_corresponds_id[$each_plan["address_id"]])->unique()->implode(",");

                            // 最も遅い終了日の取得
                            $latest_end_date=$each_plan_by_round->max("end_date");

                            return
                            [
                            $key_by_round=>[
                                "sub_lists"=>$sub_lists,
                                "city_lists"=>$city_lists,
                                "end_date"=>$latest_end_date
                            ]];
                        })//round_numberでのgroupBy
                    ])  //メイン案件でのgroupBy
            ]])//営業所でのgroupby
         ])//日付でのgroupby
         );
    }
}
