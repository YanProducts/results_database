<?php

namespace App\Support\ProjectOperator;

use Illuminate\Support\Facades\Log;

//案件を営業所に振る際におけるCSV処理
class DispatchCSVProcessor{
    // ファイルから取得する全体の流れ
    public static function get_data_in_files($files){


        foreach($files as $file){
            // メイン案件名
            $main_project_name=pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

            // ファイルにアクセス
            $handle = fopen($file->getRealPath(), 'r');
            // ファイルのの内容を見る(fgetsCSVが自動的に1行ずつ見てくれる)
            $one_flag=false;
            // 返却する案件=>town,dateのリスト(内容違いの同じ案件が登録されるケースを考え、無条件での初期化はしない)
            if(!isset($return_sets[$main_project_name])){
                $return_sets[$main_project_name]=[];
            }
            // サブ案件リスト
            $sub_projects_lists=[];
            // 各案件リスト
            while (($row = fgetcsv($handle)) !== false) {
                if(!$one_flag){
                    // CSVの1行目は併配の名前を入れる
                    $sub_projects_lists=self::get_heihai_projects_name($row);
                    //   初回のみone_flagがfalseに
                     $one_flag=true;
                     continue;
                }


              $return_sets=self::get_each_town_data($row,$main_project_name,$sub_projects_lists,$return_sets);
            }
        }


        //PHPのforeachはスコープを作らないのでこれでOK
        return $return_sets;

    }

    // 併配のプロジェクトの名前の取得(行の１行目)
    public static function get_heihai_projects_name($row){
        // １行目の場合、案件名を取得し保存する
        for($column=5;$column<count($row);$column++){
            $sub_projects_lists[$column-5]=$row[$column];
        }
        return $sub_projects_lists;
    }


    // 各csvのファイル内部での2行目以下の町目記入の処理
    public static function get_each_town_data($row,$main_project_name,$sub_projects_lists,$return_sets){

            $each_town_list=[
                "start_date"=>$row[0],
                "end_date"=>$row[1],
                // 現在のCSVデータには県のデータがない
                "city"=>$row[2],
                "town"=>$row[3]
            ];

                // メイン案件リストに追加(同じ案件内でも併配によって期日が違う場合があるので、Dateも1つずつ行う)
                $return_sets[$main_project_name][]=$each_town_list;

                // 併配リストに「○」がついているとき、サブ案件リストに追加(単配は除外)
                if(count($row)>=5){
                    for($column=5;$column<count($row);$column++){
                        if(in_array($row[$column],["〇","○","○"])){
                        $return_sets[$sub_projects_lists[$column-5]][]=$each_town_list;
                        }
                    }
                }

        return $return_sets;

    }

}
