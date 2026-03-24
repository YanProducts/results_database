<?php

// フォイル内部を読み込むときのバリデーション
// post時に内部チェックまですると、後に読み込む際に「２回読み込む」ことになるため、独立して行う

namespace App\Support\ProjectOperator;

use App\Exceptions\BusinessException;
use App\Support\CommonModelHelpers\AddressHelpers;
use App\Utils\DateHelper;
use App\Utils\Regex;
use Exception;

class DispatchCSVValidation{

    // そもそもファイルがCSVか
    public static function is_csv_file($handle){
        $first_row=fgetcsv($handle);
        if($first_row==false){
            throw new BusinessException("ファイルがCSV書式ではありません");
        }
    }

    // ファイルがタイトル行までしかなかった時
    public static function is_csv_contents_exists($row_num){
        if($row_num<3){
            throw new BusinessException("ファイルデータがタイトルのみになっています");
        }
    }


    // ファイルの１行目（メイン案件名）
    public static function check_first_row($row){
        if(count($row)<1){
            throw new BusinessException("メイン案件名が正しい位置にありません");
        }
        if(!Regex::check_projects_name($row[1])){
            throw new BusinessException("案件名".$row[1]."の名前はルールに沿っていません");
        }
    }
    // ファイルの２行目（サブ案件名）
    public static function check_second_row($row){
        // 単配でも4。4以下の時はエラー
        if(count($row)<4){
            throw new BusinessException("CSV書式のエラーです");
        }
        // 4要素目以降は案件名に従っているか
        if(count($row)>4){
            for($column=4;$column<count($row);$column++){
                if(!Regex::check_projects_name($row[$column])){
                    throw new BusinessException("案件名".$row[$column]."の名前はルールに沿っていません");
                }
            }

        }

    }
    //ファイルの３行目以下（日付・住所・○か否か・また列の数は２行目と全て同じか）
    public static function check_data_row($second_row_count,$row_num,$row,$main_project_name){
        // データの個数違い
        if(count($row)!==$second_row_count){
            throw new BusinessException("メイン案件名が".$main_project_name."のファイルの\n".$row_num."行目のデータの数が違う行が存在します");
        }
        // 日付の書式
        if(DateHelper::is_Ymd_date($row[0]) || DateHelper::is_Ymd_date($row[1])){
            throw new BusinessException("メイン案件名が".$main_project_name."のファイルの\n".$row_num."行目の日付の書式の異常です");
        }

        // 日付の順序
        if(!DateHelper::is_chronological($row_num[0],$row_num[1])){
                throw new BusinessException("メイン案件名が".$main_project_name."のファイルの\n".$row_num."行目の開始日が終了日より後になっています");
        }

        if(!AddressHelpers::is_address_exists($row[2],$row[3])){
            throw new BusinessException("メイン案件名が".$main_project_name."のファイルの".$row_num."行目の\n".$row[2].$row[3]."という町目が町目データにありません");
        }

    }


}
