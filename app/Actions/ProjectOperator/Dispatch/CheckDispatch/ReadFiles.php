<?php
// 投稿したファイル内部での重複チェック
namespace App\Actions\ProjectOperator\Dispatch\CheckDispatch;

use App\Support\Common\ModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Log;

class ReadFiles{
    // 同じ案件同じ町目のものを返す
    public static function check_same_projects_and_towns_in_files($project_and_towns){
        // 重複案件のセット
        $duplicates=[];
        // 全案件と町目のセット
        $all_project_town_sets=[];

        // 各プロジェクトを見ていく
        foreach($project_and_towns as $each_project_sets){
            // そのメイン案件内での重複チェック
            [$all_project_town_sets,$duplicates]=self::each_town_in_project_check($each_project_sets["main"],$all_project_town_sets,$duplicates);

           // サブ案件セット
           // それぞれのサブ案件を見ていく
           foreach($each_project_sets["sub"] as $each_sub){
            [$all_project_town_sets,$duplicates]=self::each_town_in_project_check($each_sub,$all_project_town_sets,$duplicates);
           }
        }
        return $duplicates;
    }

    // それぞれのプロジェクトの内部のチェック
    public static function each_town_in_project_check($each_set,$all_project_town_sets,$duplicates){
            // 案件名
           $project=$each_set["project_name"];
           // 案件内の住所配列
           $date_town_sets_in_project=array_map(fn($each_town_data)=>$each_town_data["city"].$each_town_data["town"],$each_set["date_town_sets"]);

            // 同じファイルで同じ町目が存在していていれば町目リストに入れる
            if(!empty($duplicates_in_same_file=(array_filter(array_count_values($date_town_sets_in_project),fn($counts)=>$counts>1)))){
                $duplicates[]=["projectName"=>$project,"address"=>array_keys($duplicates_in_same_file),"in_same_file"=>true];
           }

            // メイン案件のキーがこれまでの既出の場合
           if(array_key_exists($project,$all_project_town_sets)){
            $town_sets_in_the_project=$all_project_town_sets[$project];
                // 町目と案件セットが既出のときは重複リストに記入
                if(!empty($intersects=array_intersect($town_sets_in_the_project,$date_town_sets_in_project))){
                    foreach($intersects as $intersect){
                        $duplicates[]=["projectName"=>$project,"address"=>$intersect,"in_same_file"=>false];
                        }
                }
                // このループを既存リストに入れる
                $all_project_town_sets[$project]=array_unique([...$town_sets_in_the_project,...$date_town_sets_in_project]);
           }else{
            // 既出ではない場合は新たな既存リストにセット
            $all_project_town_sets[$project]=array_unique($date_town_sets_in_project);
           }

           return [$all_project_town_sets,$duplicates];
    }
}
