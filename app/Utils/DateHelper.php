<?php
// Date系列のヘルパー関数
namespace App\Utils;

use App\Exceptions\BusinessException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DateHelper{
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

}
