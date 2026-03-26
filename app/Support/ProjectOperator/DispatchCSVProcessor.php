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

            // サブ案件リスト
            $sub_projects_lists=[];
            // 何行目か
            $row_num=1;

            // 各案件リスト
            while (($row = fgetcsv($tmp_handler)) !== false) {
                if($row_num==1){
                    // メインプロジェクト名を取得（この内部でエラーチェック）
                    $main_project_name=self::get_main_projects_name($row);
                    // 返却する案件=>town,dateのリスト(内容違いの同じ案件が登録されるケースを考え、無条件での初期化はしない)
                    if(!isset($return_sets[$main_project_name])){
                        $return_sets[$main_project_name]=[];
                    }

                }else if($row_num==2){
                    // CSVの1行目は併配の名前を入れる（この内部でエラーチェック）
                    $sub_projects_lists=self::get_heihai_projects_name($row);
                    $second_row_count=count($row);
                }else{
                    // 各データを入れる（この内部でエラーチェック）
                    $return_sets=self::get_each_town_data($second_row_count,$row_num,$row,$main_project_name,$sub_projects_lists,$return_sets);
                }
                $row_num++;
            }
        }
        // データがタイトル行までしか存在しないときを弾く
        DispatchCSVValidation::is_csv_contents_exists($row_num);

        //PHPのforeachはスコープを作らないのでこれでOK
        return $return_sets;
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
    public static function get_each_town_data($second_row_count,$row_num,$row,$main_project_name,$sub_projects_lists,$return_sets){

            //データのチェック
            DispatchCSVValidation::check_data_row($second_row_count,$row_num,$row,$main_project_name);

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
