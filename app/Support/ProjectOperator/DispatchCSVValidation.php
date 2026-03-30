<?php

// フォイル内部を読み込むときのバリデーション
// post時に内部チェックまですると、後に読み込む際に「２回読み込む」ことになるため、独立して行う

namespace App\Support\ProjectOperator;

use App\Exceptions\BusinessException;
use App\Support\CommonModelHelpers\AddressHelpers;
use App\Utils\DateHelper;
use App\Utils\Regex;
use Exception;

use function PHPUnit\Framework\isNumeric;

class DispatchCSVValidation{

    // そもそもファイルがCSVとして読み取り可能か(試しに中身を読み取る。その後にファイルポインタを戻す)
    public static function is_csv_file($handle){
        $first_row=fgetcsv($handle);
        if($first_row==false){
            throw new BusinessException("ファイルがCSV書式ではありません");
        }
        rewind($handle);
    }

    // ファイルがタイトル行までしかなかった時
    public static function is_csv_contents_exists($row_num){
        if($row_num<3){
            throw new BusinessException("ファイルデータがタイトルのみになっています");
        }
    }

    // 同じメイン案件が20個以上送られていたとき
    public static function check_same_name_main_project_counts_limit($main_project_name,$round_number,$round_limit){
        if($round_number>$round_limit){
            throw new BusinessException("同案件は".$round_limit."ファイルまでしか送信できません。\n案件名".$main_project_name);
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
        // 単配でも5。5以下の時はエラー
        if(count($row)<5){
            throw new BusinessException("CSV書式のエラーです");
        }
        // 4要素目以降~MapNoの手前までは案件名に従っているか
        if(count($row)>4){
            for($column=4;$column<count($row)-1;$column++){
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
        if(!DateHelper::is_chronological($row[0],$row[1])){
                throw new BusinessException("メイン案件名が".$main_project_name."のファイルの\n".$row_num."行目の開始日が終了日より後になっています");
        }

        // 住所に存在
        if(!AddressHelpers::is_address_exists($row[2],$row[3])){
            throw new BusinessException("メイン案件名が".$main_project_name."のファイルの".$row_num."行目の\n".$row[2].$row[3]."という町目が町目データにありません");
        }

        // mapNoに数字以外が含まれていたらアウト
        $map_number=$row[count($row)-1];
        if(!ctype_digit($map_number)){
            throw new BusinessException("メイン案件名が".$main_project_name."のファイルの".$row_num."行目の\nマップの番号が数字ではありません");
            }

        // マップ番号100以降はアウト(変更可能性はあり)
        if($row[count($row)-1]>100){
            throw new BusinessException("メイン案件名が".$main_project_name."のファイルの".$row_num."行目の\nマップの番号が100を超えています");
        }

    }


}
