<?php
// Date系列のヘルパー関数
namespace App\Utils;

use App\Exceptions\BusinessException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DateHelper{

    // Y-m-d型かのチェック
    public static function is_Ymd_date($value){
        // valueがY-m-d型の時はCarbonに変換できる
        $date=Carbon::createFromFormat("Y-m-d",$value);
        if($date->format("Y-m-d")!=$value){
            return false;
        }
        return true;
    }

    //2つの日付の大小関係をチェック time1の方が前ならばOK(Carbon::parseはできる前提だが、date変換できない時は例外を投げエラーページへ)
    public static function is_chronological($time1,$time2){

        if(Carbon::parse($time1)->gt(Carbon::parse($time2))){
            return false;
        }
        return true;
    }

    public static function check_and_get_base_Ymd_date($base_Ymd_date){
        // nowの場合は空白、それ以外の場合はCarbonで変換
        if($base_Ymd_date!==""){
            if(!self::is_Ymd_date($base_Ymd_date)){
                throw new BusinessException("日付の取得のエラーです");
            }
            $base_date=Carbon::parse($base_Ymd_date);
        }else{
            $base_date=Carbon::now();
        }

        return $base_date;
    }

    // 本日から指定後の日付から、指定後の日付を返す
    // 基準日はY-m-d型、もし空白ならnow(0を返す
    public static function get_start_and_end_days($base_Ymd_date,$start_offset,$end_offset){

        // 基準日が日付になっているかを確認し、基準日の入力がある場合はdateかチェック
        $base_date=self::check_and_get_base_Ymd_date($base_Ymd_date);

        return[
            "start"=>$base_date->addDays($start_offset),
            "end"=>$base_date->addDays($end_offset)
        ];
    }

    // 期間内の日付におけるキー(Y-m-d)と値(n月j日)の配列を返す(selectBoxなどで使う想定)
    public static function get_date_key_value_sets_for_view($base_Ymd_date,$start_offset,$end_offset){

        // 基準日が日付になっているかを確認し、基準日の入力がある場合はdateかチェック
        $base_date=self::check_and_get_base_Ymd_date($base_Ymd_date);

        // 基準の日
        for($add_day=$start_offset;$add_day<$end_offset;$add_day++){
            // Carbonはmutable
            $base_date->addDay();
            $date_key_value_sets[$base_date->format("Y-m-d")]=$base_date->format("n月j日");
        }
        return $date_key_value_sets;
    }


}
