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
    }

    //2つの日付の大小関係をチェック time1の方が前ならばOK(Carbon::parseはできる前提だが、date変換できない時は例外を投げエラーページへ)
    public static function is_chronological($time1,$time2){

        if(Carbon::parse($time1)->gt(Carbon::parse($time2))){
            return false;
        }
        return true;
    }

    // 本日から指定後の日付から、指定後の日付を返す
    // 基準日はY-m-d型、もし空白なら
    public static function get_start_and_end_days($base_Ymd_date,$start_offset,$end_offset){
        // nowの場合は空白、それ以外の場合はCarbonで変換
        if($base_Ymd_date!==""){
            self::is_Ymd_date($base_Ymd_date);
            $base_date=Carbon::parse($base_Ymd_date);
        }else{
            $base_date=Carbon::now();
        }
        return[
            "start"=>$base_date->addDays($start_offset),
            "end"=>$base_date->addDays($end_offset)
        ];
    }


}
