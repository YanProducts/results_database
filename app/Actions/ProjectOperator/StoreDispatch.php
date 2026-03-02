<?php

namespace App\Actions\ProjectOperator;

use App\Models\DistributionPlan;
use App\Models\Project;

// 営業所ごとに登録する
class StoreDispatch{

    // 案件データを営業所に登録
    public static function send_projects_to_branch($request){
        // ファイルの取得(配列で)
        $files=$request->fileSets;
        // CSVから案件名=>[town,start,end]の入れ子配列の取得
         $project_name_and_towns=self::get_data_in_files($files);
        // Projectモデルに案件データを入れる（併配含む）
        self::store_projects_data($request);

        // DistributionRecordに案件Idと町目を入れる
        self::store_distribution_data($request);

    }

    public static function get_data_in_files($files){
        foreach($files as $file){
            // メイン案件名
            $main_project_name=$file->getClientOriginalName();
            // ファイルにアクセス
             $handle = fopen($file->getRealPath(), 'r');
            // ファイルのの内容を見る(fgetsCSVが自動的に1行ずつ見てくれる)
            $one_flug=false;
            // サブ案件リスト
            $sub_projects_lists=[];
            while (($row = fgetcsv($handle)) !== false) {
                if(!$one_flug){
                    // １行目の場合

                    $one_flug=true;
                    return;
                }

                // $row[0], $row[1], $row[2] ...

            }
        }
        return [];
    }


        // ファイル名とファイルの１〜２列目から、メイン案件の町名の配列取得
     public static function get_main_project_sets(){
            return[];
     }

    // ファイルの１行目の３列目以降と２行目以下の町名と○xから併配の配列を取得
    public static function get_sub_projects_sets(){
        return [];
    }

    // projectのsql挿入(日付と名前)
    // むしろProjectテーブルいらないかも！？
    // 今後にProjectの大元を管理することも含めておいておく
    public static function store_projects_data(){

        $project=new Project();

    }

    // 配布データに町目だけ入れる
    public static function store_distribution_data($request){
        $disribution_plan=new DistributionPlan();

    }



}
