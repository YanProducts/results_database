<?php

// 日付とプランidを紐づける操作ページで使用するインデックス作成
namespace App\Actions\BranchManager\Assgin\DataFetches;

use App\Exceptions\BusinessException;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Log;

class GetDatePlanIndex{
    public static function get_date_projects_index($date_sets,$project_and_towns,$assigned_days){
        // 返却用
        $return_sets=[];

        // 日付を1つずつみていく
        foreach(array_keys($date_sets) as $date){
            $return_sets[$date]=[];

            // 割当済なら次の日へ
            if($assigned_days->contains($date)){
                continue;
            }

            // 案件セットを1つずつみていく(round_number_indexは期間内で何回目か：最終的にキーになる)
            foreach($project_and_towns as $project_name_with_round_number=>$each_project_sets){
                    if(collect($each_project_sets["each_sets"])->contains(fn($each_set)=>$each_set["start_date"]<=$date && $each_set["end_date"]>=$date)){
                        array_push($return_sets[$date],$project_name_with_round_number);
                }
            }
        }

    // date=>その日に少しでも行くことのできるprojectsのセットの形で返却
    return $return_sets;

    }


    // 本来は一緒にするが、バグ防止のため。ひとまず新たに作成
    public static function get_date_projects_index_for_simple($date_sets,$project_and_maps,$assigned_days){

        // 返却用
        $return_sets=[];

        // 日付を1つずつみていく
        foreach(array_keys($date_sets) as $date){
            $return_sets[$date]=[];

            // 割当済なら次の日へ
            if($assigned_days->contains($date)){
                continue;
            }

            // 案件セットを1つずつみていく
            foreach($project_and_maps as $project_name=>$each_project_sets_with_round_number){
                // round_numberでの小分けを1つずつ見ていく
                foreach($each_project_sets_with_round_number as $round_number=>$each_project_sets){
                    if(collect($each_project_sets["each_sets"])->contains(fn($each_set)=>$each_set["start_date"]<=$date && $each_set["end_date"]>=$date)){
                        array_push($return_sets[$date], ["project_name"=>$project_name,"round_number"=>$round_number]);
                    };
                }
            }
        }

    // date=>その日に少しでも行くことのできるprojectsのセットの形で返却
    return $return_sets;

    }


    // 案件のみ版の配列作成(日付=>projectPlannedのid,表示用の案件名(roundNumber考慮),住所セット（説明用）])
    // projectPlannedCountのidを取得しておけば、その後にprojectIdもroundNumberも取得可能なので、この時点でセットする
    public static function get_project_data_for_project_only_pattern($date_sets,$projects_sets,$project_and_towns,$assigned_days){



    // projects_setsのキーの中に、
    // project_and_townsのキーが含まれない？
    // いや、これは案件登録時に、おそらく案件全体テーブルには2回目3回目とround_numberが加算されているが、planのデータには加算されていないのが原因！





        // date込みの配列
        $return_sets=[];
        foreach(array_keys($date_sets) as $date){

            // 割当済なら次の日へ
            if($assigned_days->contains($date)){
                continue;
            }

            //この日のデータ
            $project_in_the_date=$projects_sets->where("start_date","<=",$date)->where("end_date",">=",$date)->groupBy(["project_id","round_number"]);

            // $projects_setsは期間内に営業所に来ている案件における[メイン案件名,roundNumbe,project_plannedのId,start_dare,end_date](project_planned_countより取得)。そこからその日が絞り込まれたのが$project_in_the_date
            // 変換した配列の作成
            $formatted_project_data_in_the_date=collect();
            foreach($project_in_the_date as $project_id=>$data_with_round_number){
                $project_name=ProjectHelpers::get_project_name_from_id($project_id);
                $round_number_index=0;
                foreach($data_with_round_number as $round_number=>$each_project_data_base){
                $round_number_index+=1;
                //  round_numberでgroupByしたまとまりがeach_project_data_baseには格納
                // 同じプロジェクトId、同じplace_id、同じround_numberの案件は必ず1つ。それ以上の場合はエラー
                if(count($each_project_data_base)>1){
                    throw new BusinessException("予期せぬエラーです");
                }
                // 案件セットの取得
                $each_project_data=$each_project_data_base[0];

                // キーはproject?planned_countのidだが、Jsxが並び替えることを考えid_とつける
                $formatted_project_data_in_the_date->put("id_".$project_plan_id=$each_project_data["id"],[
                    "project_plan_Id"=>$project_plan_id, //これだけで投稿時に特定できる
                    "project_name_for_view"=>$round_number_index==1 ? $project_name : $project_name.":".$round_number_index, // 同じメイン案件のものがその日のデータにいくつあるか。複数のものにはround_numberもfor_viewの名前に入れる(同じ日のことなので、round_numberがそのまま入るわけではない)
                    "address_sets"=>array_column($project_and_towns[$project_name."|".$round_number]["each_sets"] ?? [],"address_name")//project_name_and_townsは既に営業所ではソート済。同じ期間内=前から1月以内のため、案件名で最新案件idは発見、ronnd_numberで1つの案件に決定される。[project_name|round_number]がキーになっている
                ]);

                }
            }

            $return_sets[$date]=$formatted_project_data_in_the_date;
        }

        // 日付=>[案件全体(project_planned_count)id_id名]=>[案件全体(project_planned_count)テーブルのid,表示名,市町村セット]
        return $return_sets;
    }




}
