<?php

namespace App\Support\ProjectOperator;

use App\Exceptions\BusinessException;
use App\Utils\FileHelper;
use App\Utils\Regex;
use Illuminate\Support\Facades\Log;

//案件を営業所に振る際におけるCSV処理
class DispatchCSVProcessor{
    // ファイルから取得する全体の流れ
    public static function get_data_in_files($files){
        // 返却用
        $return_sets=[];
        foreach($files as $file){

            // ファイルのの内容を見る(fgetsCSVが自動的に1行ずつ見てくれる)

            // BOMを削除したポインタを返す
            $tmp_handler=FileHelper::get_non_BOM_pointer($file);

           // そもそもファイルがCSVとして読み取り可能か(試しに中身を読み取る。その後にファイルポインタを戻す)
            DispatchCSVValidation::is_csv_file($tmp_handler);

            // 何行目か
            $row_num=1;

            // csvデータの内部を取得
            [$return_sets,$row_num]=self::get_csv_inner_data($tmp_handler,$return_sets,$row_num);
        }

        // データがタイトル行までしか存在しないときを弾く
        DispatchCSVValidation::is_csv_contents_exists($row_num);


        //PHPのforeachはスコープを作らないのでこれでOK
        return $return_sets;
    }

    // 内部データの取得
    public static function get_csv_inner_data($tmp_handler,$return_sets,$row_num){
            // サブ案件リスト
            $sub_projects_lists=[];

            // 各案件リスト
            while (($row = fgetcsv($tmp_handler)) !== false) {
                if($row_num==1){
                  // １行目の処理(同じメイン案件名(ファイルのテーマ名)が何回目の登場かのdo~whileを行い、ファイル名とメイン案件名を記入し、配列を返す)
                    [$return_sets,$return_sets_key,$main_project_name]=self::get_first_row_data($row,$return_sets);

                }else if($row_num==2){
                    // CSVの1行目は併配の名前を入れる（この内部でエラーチェック）
                    $sub_projects_lists=self::get_heihai_projects_name($row);
                    $second_row_count=count($row);
                }else{

                    // 各データを入れる（この内部でエラーチェック）
                    $return_sets=self::get_each_town_data($second_row_count,$row_num,$row,$main_project_name,$sub_projects_lists,$return_sets,$return_sets_key);

                }
                $row_num++;
            }
        return [$return_sets,$row_num];
    }

    // １行目の処理(同じメイン案件名(ファイルのテーマ名)が何回目の登場かのdo~whileを行い、ファイル名とメイン案件名を記入し、配列を返す)
    public static function get_first_row_data($row,$return_sets){

                   // 同じメイン案件名が何回出てくるか
                    $round_number=0;

                    // メインプロジェクト名を取得（この内部でエラーチェック）
                    $main_project_name=self::get_main_projects_name($row);

                    // 同案件のファイルをいくつまで同時送信して良いとするか
                    $round_limit=20;

                    do{
                      DispatchCSVValidation::check_same_name_main_project_counts_limit($main_project_name,$round_number,$round_limit);
                      if(!isset($return_sets[$main_project_name."_".$round_number])){
                        // return_sets_keyはイメージ的にはファイル名のようなもの。この中にmainで案件名とdateと町目、subで案件名ごとにdateと町目を記載
                        $return_sets_key=$main_project_name."_".$round_number;
                        $return_sets[$return_sets_key]["main"]["project_name"]=$main_project_name;
                        break;
                     }
                     $round_number++;
                    }while($round_number<$round_limit+1);
                return [$return_sets,$return_sets_key,$main_project_name];
    }

    // メインプロジェクトの名前の取得
    public static function get_main_projects_name($row){
        DispatchCSVValidation::check_first_row($row);
        return $row[1];
    }

    // 併配のプロジェクトの名前の取得(行の２行目)
    public static function get_heihai_projects_name($row){
        DispatchCSVValidation::check_second_row($row);
        // ２行目の場合、rowデータの案件名(5列目以降)を取得し保存する
        return array_slice($row,4);
    }


    // 各csvのファイル内部での3行目以下の町目記入の処理
    public static function get_each_town_data($second_row_count,$row_num,$row,$main_project_name,$sub_projects_lists,$return_sets,$return_sets_key){

            //データのチェック
            DispatchCSVValidation::check_data_row($second_row_count,$row_num,$row,$main_project_name);

            $each_town_list=[
                "start_date"=>$row[0],
                "end_date"=>$row[1],
                // 現在のCSVデータには県のデータがない
                "city"=>$row[2],
                "town"=>$row[3],
                // 分割
                "map_number"=>$row[count($row)-1]
            ];

                // メイン案件リストに追加(同じ案件内でも併配によって期日が違う場合があるので、Dateも1つずつ行う)
                $return_sets[$return_sets_key]["main"]["date_town_sets"][]=$each_town_list;

                // 併配リストに「○」がついているとき、サブ案件リストに追加(単配は除外)
                // それぞれの行の最後はMapナンバー
                if(count($row)>=6){
                    for($column=5;$column<count($row)-1;$column++){
                        if(in_array($row[$column],["〇","○","○"])){
                            $return_sets[$return_sets_key]["sub"][]=["project_name"=>$sub_projects_lists[$column-5],"date_town_sets"=>$each_town_list];
                        }
                    }
                }

        return $return_sets;

    }

}
